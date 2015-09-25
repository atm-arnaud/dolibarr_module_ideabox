<?php

class TIdeabox extends TObjetStd
{
    
    function __construct() 
    {   
    	/*
		 * vars
		 * 
		 * fk_usergroup
		 * label
		 * 
		 */
		 
        $this->set_table(MAIN_DB_PREFIX.'ideabox');
        $this->add_champs('fk_usergroup',array('type'=>'integer', 'index'=>true));
        $this->add_champs('label',array('type'=>'varchar', 'length' => 50));
	}
    
    public function getTradUserGroup($dolidb)
    {
        $usergroup = new UserGroup($dolidb);
        $usergroup->fetch($this->fk_usergroup);
        return $usergroup->nom;
    }
}


class TIdeaboxItem extends TObjetStd
{
        
    function __construct() 
    {
    	/*
		 * vars
		 * 
		 * fk_ideabox
		 * fk_user
		 * label
		 * description
		 * 
		 */
		 
        $this->set_table(MAIN_DB_PREFIX.'ideaboxitem');
        $this->add_champs('fk_ideabox, fk_user',array('type'=>'integer', 'index'=>true));
        $this->add_champs('label',array('type'=>'varchar', 'length' => 150));
        $this->add_champs('description',array('type'=>'text'));
        
        $this->_init_vars();
        
        $this->start();
        
        $this->qty=1;
        $this->product_type=1;        
    }
}