<?php

class OnePressPR108Activation extends FactoryPR108Activation {
    
    public function activate() {
        
            $this->license(array(
                'Category'      => 'free',
                'Build'         => 'premium',
                'Title'         => 'OnePress Zero License',
                'Description'   => 'Please, activate the plugin to get started. Enter a key 
                                    you received with the plugin into the form below.'
            ));
        

    }
}