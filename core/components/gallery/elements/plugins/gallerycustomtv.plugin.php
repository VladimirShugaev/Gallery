<?php
/**
 * @package gallery
 */
$corePath = $modx->getOption('gallery.core_path',null,$modx->getOption('core_path').'components/gallery/');
switch ($modx->event->name) {
    case 'OnTVInputRenderList':
        $modx->event->output($corePath.'elements/tv/input/');
        break;
    case 'OnTVOutputRenderList':
        $modx->event->output($corePath.'elements/tv/output/');
        break;
    case 'OnTVOutputRenderPropertiesList':
        $modx->event->output($corePath.'elements/tv/properties/');
        break;
    case 'OnDocFormPrerender':
        $gallery = $modx->getService('gallery','Gallery',$modx->getOption('gallery.core_path',null,$modx->getOption('core_path').'components/gallery/').'model/gallery/',$scriptProperties);
        if (!($gallery instanceof Gallery)) return '';

        /* assign gallery lang to JS */
        $modx->lexicon->load('gallery:default');
        $_lang_topics = $modx->smarty->get_template_vars('_lang_topics');
        $_lang_topics .= ',gallery:default';
        $modx->smarty->assign('_lang_topics',$_lang_topics);

        /* get gallery action */
        $action = $modx->getObject('modAction',array(
            'namespace' => 'gallery',
            'controller' => 'index',
        ));
        $modx->regClientStartupHTMLBlock('<script type="text/javascript">
        Ext.onReady(function() {
            GAL.config = {};
            GAL.config.connector_url = "'.$gallery->config['connectorUrl'].'";
            GAL.action = "'.($action ? $action->get('id') : 0).'";
        });
        </script>');
        $modx->regClientStartupScript($gallery->config['assetsUrl'].'js/mgr/gallery.js');
        $modx->regClientStartupScript($gallery->config['assetsUrl'].'js/mgr/widgets/album/album.items.view.js');
        $modx->regClientStartupScript($gallery->config['assetsUrl'].'js/mgr/widgets/album/album.tree.js');
        $modx->regClientStartupScript($gallery->config['assetsUrl'].'js/mgr/tv/gal.browser.js');
        $modx->regClientStartupScript($gallery->config['assetsUrl'].'js/mgr/tv/galtv.js');
        break;
}
return;