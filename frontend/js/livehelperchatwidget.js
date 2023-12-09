var LHC_API = LHC_API||{};
var chatWidget = null; // variable to store the reference to the chat widget element

document.addEventListener('consent.ready', function(e) {
    myConsentFunction(e.detail);
});
document.addEventListener('consent.updated', function(e) {
    myConsentFunction(e.detail);
});

function myConsentFunction(detail) 
{
    if (detail !== null && typeof detail.gb3d_livechathelper_consent !== 'undefined') {
        if (detail.gb3d_livechathelper_consent === true) {
            //Here you need to add your Live Helper Chat embedded code snippet, without the <script></script> identifier please also add to the var po line the following attribute: po.id = 'lhc_widget_container'; this id makes it easier to hide the correct DOM Element
            //TODO add a setting in the backend to make it easiert to add the embedded code from live helper chat widget
            LHC_API.args = {mode:'widget',lhc_base_url:'//gingerbeard3d.livehelperchat.com/',wheight:450,wwidth:350,pheight:520,pwidth:500,leaveamessage:true,check_messages:false,lang:'eng/'};
            (function() {
            var po = document.createElement('script'); po.type = 'text/javascript'; po.setAttribute('crossorigin','anonymous'); po.async = true; po.id = 'lhc_widget_container';
            var date = new Date();po.src = '//gingerbeard3d.livehelperchat.com/design/defaulttheme/js/widgetv2/index.js?'+(""+date.getFullYear() + date.getMonth() + date.getDate());
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
            })();
            //console.log('gb3d_livechathelper_consent has consent!');
        } else {
            // Remove the LiveHelperChat widget script
            var scripts = document.getElementsByTagName('script');
            for (var i = 0; i < scripts.length; i++) {
                if (scripts[i].src && scripts[i].src.includes('livehelperchat')) {
                    scripts[i].parentNode.removeChild(scripts[i]);
                }
            }

            // Clear any variables or components related to the chat
            LHC_API = {}; // Clear LHC_API object
            hideChatWidget();
            console.log('gb3d_livechathelper_consent has NO consent. Widget destroyed.');
        }
    }
}

function hideChatWidget() 
{
    // Hide the chat widget container or element
    if (chatWidget) 
    {
        console.log('Display none');
        chatWidget.style.display = 'none';
    }
}

// Add an event listener to capture the creation of the chat widget element
document.addEventListener('DOMNodeInserted', function (event) 
{
    if (event.target && event.target.id && event.target.id.startsWith('lhc_container_v2')) 
    {
        console.log('ChatWidgetSetTarget');
        chatWidget = event.target;
        console.log(chatWidget);
    }
});
