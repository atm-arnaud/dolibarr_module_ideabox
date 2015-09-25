<?php
    require('config.php');
    require('./class/ideabox.class.php');
    require('./lib/ideabox.lib.php');
    
    $langs->load('ideabox@ideabox');
    
    dol_include_once('/core/class/html.form.class.php');
    dol_include_once("/core/class/html.formother.class.php");
    
    
    $action=__get('action','view');
    $id = __get('id', 0);
    $ATMdb=new TPDOdb;
    

    switch($action) {
        case 'view':        
            $idea=new TIdeabox;
            $idea->load($ATMdb, $id);
            
            _fiche($ATMdb, $idea, 'view');
            
            break;
        
        case 'new':
            $idea=new TIdeabox;
            
            _fiche($ATMdb, $idea, 'edit');
            
            break;
    
        case 'edit':
            $idea=new TIdeabox;
            $idea->load($ATMdb, $id);
            
            _fiche($ATMdb, $idea, 'edit');
            
            break;
            
        case 'save':
            $idea=new TIdeabox;
            $idea->load($ATMdb, $id);
            $idea->set_values($_REQUEST);
            $idea->save($ATMdb);
        
            setEventMessage($langs->trans('IdeaboxSaveControlEvent'));
        
            _fiche($ATMdb, $idea, 'view');
            
            break;
        
        case 'delete':
            $idea=new TIdeabox;
            $idea->load($ATMdb, $id);
            $idea->delete($ATMdb);
            
            $_SESSION['AssetMsg'] = 'AssetDeleteControlEvent';
            header('Location: '.DOL_MAIN_URL_ROOT.'/custom/asset/list_control.php');
            
            break;
            
        case 'deleteValue':
            $idea=new TIdeabox;
            $idea->load($ATMdb, $id);
            
            if ($idea->removeChild('TIdeaboxItem', __get('id_value',0,'integer'))) 
            {
                $idea->save($ATMdb);
                setEventMessage($langs->trans('AssetMsgDeleteControlValue'));
            }
            else setEventMessage($langs->trans('AssetErrDeleteControlValue'));
            
            _fiche($ATMdb, $idea, 'view');
            
            break;

            
        default:
            $idea=new TIdeabox;
            $idea->load($ATMdb, $id);
            
            _liste($ATMdb, $idea, 'view');
            
            break;
    }
    

function _fiche(&$ATMdb, &$idea, $mode='view', $editValue=false) {
    global $db,$langs;

    $sql = 'SELECT rowid, nom 
            FROM '.MAIN_DB_PREFIX.'usergroup';
    $usergroup = array();
    $ATMdb->Execute($sql);
    while ($ATMdb->Get_line())
    {
        $usergroup[] = array(
            'rowid' => $ATMdb->Get_field('rowid')
            ,'nom' => $ATMdb->Get_field('nom')
        );
    }
    
    /******/
    $TBS=new TTemplateTBS();
    $TBS->TBS->protect=false;
    $TBS->TBS->noerr=true;

    $form=new TFormCore($_SERVER['PHP_SELF'], 'form', 'POST');
    $form->Set_typeaff('view');
    
    
    
    $TIdeaboxItem = _fiche_ligne_ideabox_item($PDOdb, $idea->getId());
    
    print $TBS->render('tpl/fiche_of_ideabox.tpl.php'
        ,array(
            'TIdeaboxItem'=>$TIdeaboxItem
        )
        ,array(
            'ideabox'=>array(
                'id'=>(int) $idea->getId()
                ,'nom'=> $idea->getLabel()
            )
            ,'view'=>array(
                'type'=>'showficheideabox'
                ,'url'=>dol_buildpath('/ideabox/ideabox.php',2)
            )
        )
    );
    
    $form->end();
    /*******/
}


function _liste(&$ATMdb, &$idea, $mode='view', $editValue=false) {
    global $db,$langs;

    $sql = 'SELECT rowid, nom 
            FROM '.MAIN_DB_PREFIX.'usergroup';
    
    $ATMdb->Execute($sql);
    while ($ATMdb->Get_line())
    {
        $usergroup[] = array(
            'rowid' => $ATMdb->Get_field('rowid')
            ,'nom' => $ATMdb->Get_field('nom')
        );
    }
    
    llxHeader('',$langs->trans('IdeaboxAddItem'),'','');
    
    $ideabox = new TIdeabox;
    $r = new TSSRenderControler($ideabox);
    
    $sql = 'SELECT rowid, label, fk_usergroup';
    $sql .= ' FROM '.MAIN_DB_PREFIX.'ideabox ib';
    
    $orderBy['ib.rowid']='DESC';
    $THide = array();
    
    $r->liste($ATMdb, $sql, array(
        'limit'=>array()
        ,'orderBy'=>$orderBy
        ,'subQuery'=>array()
        ,'link'=>array(
            'label'=>'<a href="ideabox.php?action=viewid=@rowid@">'.img_picto('','object_product.png','',0).' @val@</a>'
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
            ,'messa geNothing'=>"Il n'y a aucun ".$langs->trans('OFAsset')." à afficher"
            ,'picto_search'=>img_picto('','search.png', '', 0)
        )
        ,'title'=>array(
            'rowid'=>'ID'
            ,'label'=>'Nom'
            ,'fk_usergroup'=>'Groupe utilisateur'
        )
        ,'eval'=>array(
            'fk_usergroup' => 'ideaboxGetUserGroupNom(@fk_usergroup@)'
        )
        ,'search'=>array()
    ));
    
    llxFooter();
}


function _fiche_ligne_ideabox_item(&$PDOdb, $fk_ideabox)
{
    $res = array();
    
    if (!isset($fk_ideabox) || empty($fk_ideabox) || !is_int($fk_ideabox)) return $res;
    
    $sql = 'SELECT rowid as id, label, description, fk_user';
    $sql.= ' FROM '.MAIN_DB_PREFIX.'ideaboxitem';
    $sql.= ' WHERE fk_ideabox = '.$fk_ideabox;
    
    
    $PDOdb->Execute($sql);
    while ($PDOdb->Get_line())
    {
        $res[] = array(
            'id' => $PDOdb->Get_field('id')
            ,'label' => $PDOdb->Get_field('label')
            ,'description' => $PDOdb->Get_field('description')
            ,'delete' => '<input type="checkbox" value="'.$PDOdb->Get_field('id').'" name="TIdeaboxDelete[]" />'
        );
    }
    
    return $res;
}
