<?php
App::uses('AppController', 'Controller');
/**
 * PluginViews Controller
 *
 * @property PluginView $PluginView
 */
class PluginViewsController extends AppController
{

    function beforeFilter()
    {
        parent::beforeFilter();
        $this->PermissionValidation->actionAllowed(null, 'LayoutManager',true);
    }

    function json()
    {
        $data = $this->PluginView->find('all');
        $plugins = array();
        foreach ($data as $plugin) {
            $p = $plugin['PluginView'];
            $p['plugin'] = $plugin['Plugin']['name'];
            $plugins[] = $p;
        } // for
        $this->layout = 'ajax';
        $this->response->type('json');
        $jsonString = json_encode($plugins);
        $this->set('plugins', $jsonString);
    }

}
