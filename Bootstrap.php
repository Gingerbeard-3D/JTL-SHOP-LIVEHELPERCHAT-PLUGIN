<?php declare(strict_types=1);

namespace Plugin\gb3d_livehelperchat;

use JTL\Alert\Alert;
use JTL\Catalog\Category\Kategorie;
use JTL\Catalog\Product\Artikel;
use JTL\Consent\Item;
use JTL\Events\Dispatcher;
use JTL\Events\Event;
use JTL\Helpers\Form;
use JTL\Helpers\Request;
use JTL\Link\LinkInterface;
use JTL\Plugin\Bootstrapper;
use JTL\Router\Router;
use JTL\Shop;
use JTL\Shopsetting;
use JTL\Smarty\JTLSmarty;


/**
 * Class Bootstrap
 * @package Plugin\gb3d_livehelperchat
 */
class Bootstrap extends Bootstrapper
{
    /**
    * @var TestHelper
    */
    private $helper;

    private const CONSENT_ITEM_ID = 'gb3d_livechathelper_consent';

    public function boot(Dispatcher $dispatcher)
    {
        $status = "bootfunctioncalled";
        echo"<script>console.log('$status');</script>
        $plugin = $this->getPlugin();
        
        $dispatcher->listen('shop.hook.' . \HOOK_LETZTERINCLUDE_CSS_JS, static function () {
            // set some value to registry
            Shop::set('livehelperchatwidget', 42);
        });

        $dispatcher->listen('shop.hook.' . \HOOK_LETZTERINCLUDE_INC, function () use ($plugin) {
            $logger = \method_exists($plugin, 'getLogger')
                ? $plugin->getLogger()
                : Shop::Container()->getLogService();
            if ($plugin->getConfig()->getValue('jtl_test_add_consent_item') === 'Y') {
                $state = Shop::Container()->getConsentManager()->hasConsent(self::CONSENT_ITEM_ID);
                if ($state === true) {
                    // plugin has consent - do something
                    $logger->info('Plugin {plgn} has consent!', ['plgn' => $plugin->getPluginID()]);
                }
            }
            if (Shop::has('jtl_test_foo') && $plugin->getConfig()->getValue('jtl_test_debug') === 'Y') {
                Shop::dbg(Shop::get('jtl_test_foo'), false, 'fooBar from registry:');
            }
            if (Shop::getPageType() === \PAGE_ARTIKEL) {
                $model = ModelFoo::load(['id' => 1], $this->getDB());
                if ($plugin->getConfig()->getValue('jtl_test_debug') === 'Y') {
                    Shop::dbg($model->getFoo(), false, 'Got foo value from DB:'); // quick & dirty debugging
                }
            }
        }, 10); // custom priority of "10" - lower than default
        if ($plugin->getConfig()->getValue('jtl_test_add_consent_item') === 'Y') {
            $dispatcher->listen('shop.hook.' . \CONSENT_MANAGER_GET_ACTIVE_ITEMS, [$this, 'addConsentItem']);
        }
    }
    
    /**
    * @param array $args
    */
    public function addConsentItem(array $args): void
    {
        $lastID = $args['items']->reduce(static function ($result, Item $item) {
            $value = $item->getID();

            return $result === null || $value > $result ? $value : $result;
        }) ?? 0;
        $item   = new Item();
        $item->setName('Live Helper Chat Plugin');
        $item->setID(++$lastID);
        $item->setItemID(self::CONSENT_ITEM_ID);
        $item->setDescription('Der Webservice von Live Helper Chat ermöglicht direkten Chat Support mit dem Endkunden per Chat Widget folgende Daten werden verareitet: anonymisierte IP Adresse.');
        $item->setPurpose('Live Helper Chat Widget');
        $item->setPrivacyPolicy('https://livehelperchat.com/gdpr-compliance-504a.html');
        $item->setCompany('JTL-Software-GmbH');
        $args['items']->push($item);
    }
}
