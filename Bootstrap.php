<?php declare(strict_types=1);
/**
 * @package Plugin\gb3d_livehelperchat
 * @author  Gingerbeard.3D
 */

namespace Plugin\gb3d_livehelperchat;

use JTL\Events\Dispatcher;
use JTL\Plugin\Bootstrapper;
use JTL\Smarty\JTLSmarty;

/**
 * Class Bootstrap
 * @package Plugin\gb3d_livehelperchat
 */
class Bootstrap extends Bootstrapper
{
    /**
     * @inheritdoc
     */
    public function boot(Dispatcher $dispatcher): void
    {
        parent::boot($dispatcher);
        $plugin = $this->getPlugin();
        $db     = $this->getDB();
        $cache  = $this->getCache();
        $dispatcher->listen('shop.hook.' .\CONSENT_MANAGER_GET_ACTIVE_ITEMS, function ($args) use ($plugin, $db, $cache) {
            // implement me
        });
    }
}
