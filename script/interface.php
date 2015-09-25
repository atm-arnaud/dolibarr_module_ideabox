<?php

    require('../config.php');
    
    dol_include_once('/ideabox/class/ideabox.class.php');
    
    $get = GETPOST('get');
    $put = GETPOST('put');
    
    switch ($put) {
        case 'item':
        /*
            $ideaItem=new TIdeaboxItem;
            $ideaItem->load($PDOdb, $id);
            $ideaItem->set_values($_REQUEST);
            $ideaItem->save($PDOdb);
        */
            $PDOdb=new TPDOdb;
            //$PDOdb->debug = true;
            $i = new TIdeabox;
            $i->load($PDOdb, GETPOST('fk_ideabox'));
           // var_dump($i);
            $k = $i->addChild($PDOdb, 'TIdeaboxItem');
            $i->TIdeaboxItem[$k]->set_values($_REQUEST);
            $i->save($PDOdb);
            
            break;
        
        
        default:
            
            break;
    }
