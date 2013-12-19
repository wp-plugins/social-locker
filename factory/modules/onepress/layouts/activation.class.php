<?php

class OnePressFR110Activation extends FactoryFR110Activation {
    
    public function activate() {
            
            $this->license(array(
                'Category'      => 'free',
                'Build'         => 'free',
                'Title'         => 'OnePress Public License',
                'Description'   => 'Public License is a GPLv2 compatible license. 
                                    It allows you to change this version of the plugin and to
                                    use the plugin free. Please remember this license 
                                    covers only free edition of the plugin. Premium versions are 
                                    distributed with other type of a license.'
            ));
        

    }
}