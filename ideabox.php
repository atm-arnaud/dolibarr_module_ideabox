<?php
    require('config.php');
    require('./class/ideabox.class.php');
    require('./lib/ideabox.lib.php');
    
    $langs->load('ideabox@ideabox');
    
    dol_include_once('/core/class/html.form.class.php');
    dol_include_once("/core/class/html.formother.class.php");
    
    $action=__get('action','liste');
    $id = __get('id', 0);
    $PDOdb=new TPDOdb;
    

    switch($action) {
        case 'view':
            $idea=new TIdeabox;
            $idea->load($PDOdb, $id);
            
            _fiche($PDOdb, $idea, 'view');
            
            break;
        
        case 'new':
            if($user->rights->ideabox->create < 1) accessforbidden();
            $idea=new TIdeabox;
            
            _fiche($PDOdb, $idea, 'edit');
            
            break;
    
        case 'edit':
            if($user->rights->ideabox->create < 1) accessforbidden();
            $idea=new TIdeabox;
            $idea->load($PDOdb, $id);
            
            _fiche($PDOdb, $idea, 'edit');
            
            break;
            
        case 'save':
            if($user->rights->ideabox->create < 1) accessforbidden();
            $idea=new TIdeabox;
            $idea->load($PDOdb, $id);
            $idea->set_values($_REQUEST);
            $idea->save($PDOdb);
        
            setEventMessage($langs->trans('IdeaboxUpdateSaveControlEvent'));
        
            header('Location: '.dol_buildpath('/ideabox/ideabox.php',2).'?id='.$idea->getId().'&action=view');
                        
            break;
        
        case 'delete':
            $idea=new TIdeabox;
            $idea->load($PDOdb, $id);
            if($user->rights->ideabox->create < 1 && $idea->fk_user != $user->id) accessforbidden();
            $idea->delete($PDOdb);
            
            setEventMessage($langs->trans('IdeaboxDeleteControlEvent'));
            
            header('Location: '.dol_buildpath('/ideabox/ideabox.php',2));
            
            break;
            
        case 'deleteItem':
            if($user->rights->ideabox->create < 1) accessforbidden();
            $ideaItem=new TIdeaboxItem;
            $ideaItem->load($PDOdb, $id);
            
            $idea=new TIdeabox;
            $idea->load($PDOdb, $ideaItem->fk_ideabox);
            
            $ideaItem->delete($PDOdb);
            
            setEventMessage($langs->trans('IdeaboxItemDeleteControlEvent'));
            
            header('Location: '.dol_buildpath('/ideabox/ideabox.php',2).'?id='.$idea->getId().'&action=view');
            
            break;

            
        case 'liste':
        default :
            $idea=new TIdeabox;
            $idea->load($PDOdb, $id);
            
            _liste($PDOdb, $idea, 'view');
            
            break;
    }
    

function _fiche(&$PDOdb, &$idea, $mode='view', $editValue=false) {
    global $db,$langs,$user;

    llxHeader('',$langs->trans('IdeaboxAddItem'),'','');
    
    /******/
    $TBS=new TTemplateTBS();
    $TBS->TBS->protect=false;
    $TBS->TBS->noerr=true;

    $form=new TFormCore($_SERVER['PHP_SELF'], 'form', 'POST');
    $form->Set_typeaff($mode);
    echo $form->hidden('id', $idea->getId());
    echo $form->hidden('action', 'save');
    
    $TIdeaboxItem = _fiche_ligne_ideabox_item($PDOdb, $idea->getId(), $mode);
    
    $formDoli = new Form($db);

   
    
    print $TBS->render('tpl/ideabox.tpl.php'
        ,array(
            'TIdeaboxItem'=>$TIdeaboxItem
        )
        ,array(
            'TIdeabox'=>array(
                'id'=>(int) $idea->getId()
                ,'label'=> $form->texte('', 'label', $idea->label, 80,150,'','','à saisir')
                ,'usergroup'=> ($mode == 'view' ? $idea->getNameUserGroup($db)  : $formDoli->select_dolgroups($idea->fk_usergroup,'fk_usergroup',1))
                
            )
            ,'view'=>array(
                'type'=>'showficheideabox'
                ,'mode'=>$mode
                ,'user_right'=>$user->rights->ideabox->create
                ,'url'=>dol_buildpath('/ideabox/ideabox.php',2)
            )
        )
    );
    
    $form->end();
    
    llxFooter();
}


function _liste(&$PDOdb, &$idea, $mode='view', $editValue=false) {
    global $db,$langs;

    $sql = 'SELECT rowid, nom 
            FROM '.MAIN_DB_PREFIX.'usergroup';
    
    $PDOdb->Execute($sql);
    while ($PDOdb->Get_line())
    {
        $usergroup[] = array(
            'rowid' => $PDOdb->Get_field('rowid')
            ,'nom' => $PDOdb->Get_field('nom')
        );
    }
    
    llxHeader('',$langs->trans('IdeaboxAddItem'),'','');
    
    $ideabox = new TIdeabox;
    $r = new TSSRenderControler($ideabox);
    
    $sql = 'SELECT ib.rowid, ib.label, count(ii.rowid) as ideaItem, ib.fk_usergroup';
    $sql.= ' FROM '.MAIN_DB_PREFIX.'ideabox ib';
    $sql.= ' LEFT JOIN '.MAIN_DB_PREFIX.'ideaboxitem ii ON (ii.fk_ideabox = ib.rowid)';
    $sql.= ' GROUP BY ib.rowid';
    $orderBy['ib.rowid']='DESC';
    $THide = array();
    
    $r->liste($PDOdb, $sql, array(
        'limit'=>array()
        ,'orderBy'=>$orderBy
        ,'subQuery'=>array()
        ,'link'=>array(
            'label'=>'<a href="ideabox.php?id=@rowid@&amp;action=view">'.img_picto('','object_product.png','',0).' @val@</a>'
        )
        ,'translate'=>array()
        ,'hide'=>$THide
        ,'type'=>array()
        ,'math'=>array()
        ,'liste'=>array(
            'titre'=>$langs->trans('ListOfIdeabox')
            ,'image'=>img_picto('','title.png', '', 0)
            ,'picto_precedent'=>img_picto('','back.png', '', 0)
            ,'picto_suivant'=>img_picto('','next.png', '', 0)
            ,'messa geNothing'=>"Il n'y a aucun ".$langs->trans('Ideabox')." à afficher"
            ,'picto_search'=>img_picto('','search.png', '', 0)
        )
        ,'title'=>array(
            'rowid'=>'ID'
            ,'label'=>'Nom'
            ,'ideaItem' => 'Idée(s)'
            ,'fk_usergroup'=>'Groupe utilisateur'
        )
        ,'eval'=>array(
            'fk_usergroup' => 'ideaboxGetUserGroupNom(@fk_usergroup@)'
        )
        ,'search'=>array()
    ));
    
    llxFooter();
}


function _fiche_ligne_ideabox_item(&$PDOdb, $fk_ideabox, $mode = 'view')
{
    global $user;
    $res = array();
    
    if (!isset($fk_ideabox) || empty($fk_ideabox)) return $res;
    
    $sql = 'SELECT rowid as id, label, description, fk_user';
    $sql.= ' FROM '.MAIN_DB_PREFIX.'ideaboxitem';
    $sql.= ' WHERE fk_ideabox = '.$fk_ideabox;
    
    $PDOdb->Execute($sql);
    while ($PDOdb->Get_line())
    {
        $delete = $PDOdb->Get_field('fk_user')==$user->id?"<a style=\"cursor:pointer;\" onclick=\"if (window.confirm('Voulez vous supprimer l\'idée ?')){document.location.href='?id=".$PDOdb->Get_field('id')."&action=deleteItem'};\">".img_picto('','delete.png', '', 0)."</a>":'';
        $res[] = array(
            'id' => $PDOdb->Get_field('id')
            ,'label' => $PDOdb->Get_field('label')
            ,'description' => $PDOdb->Get_field('description')
            ,'fk_user' => $PDOdb->Get_field('fk_user')
            ,'fk_user_trad' => ideaboxGetUserNom($PDOdb->Get_field('fk_user'))
            ,'delete' => $delete
        );
    }
    return $res;
}
