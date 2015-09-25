<?php
    define('INC_FROM_DOLIBAR', true);
    require_once('../config.php');
    dol_include_once('/ideabox/lib/ideabox.lib.php');

    $langs->load('ideabox@ideabox');
    $group = checkIdeabox($user->id);
    $PDOdb=new TPDOdb;
    $sql = 'SELECT rowid, label, fk_usergroup 
            FROM '.MAIN_DB_PREFIX.'ideabox';
    $ideabox = array();
    $PDOdb->Execute($sql);
    while ($PDOdb->Get_line())
    {
        $ideabox[] = array(
            'id' => $PDOdb->Get_field('rowid')
            ,'label' => $PDOdb->Get_field('label')
            ,'fk_usergroup' => $PDOdb->Get_field('fk_usergroup')
        );
    }
    $group_format = array();
    foreach($group as $item)
    {
        $group_format[$item->id] = $item->name;
    }
?>
$('document').ready(function(){
        
    function openIdeaboxWidget(){
        $('body').append('<div id="ideabox_widget"><div class="titre center">Ajouter une idée dans une boîte</div>Boîte : <select id="ideabox_widget_select"></select><br /></div>');
        $('#ideabox_widget').append('Titre : <br /><input id="ideabox_widget_label" type="text" id="ideabox_widget_label" placeholder="Titre de l\'idée" /><br />');
        $('#ideabox_widget').append('Description : <br /><textarea id="ideabox_widget_description" cols="10" rows="2" type="text" placeholder="Description de l\'idée" /><br />');
        $('#ideabox_widget').append('<div class="center" style="margin-top:5px;"><a id="ideabox_widget_valid" class="button" style="cursor:pointer;">Ajouter</a>&nbsp;'
                                + '<a id="ideabox_widget_cancel" class="button" style="cursor:pointer;">Annuler</a></div>');
    }
    
    var ideabox = new Array();
    
    <?php 
        foreach($ideabox as $item)
        {
            $valid = false;
            foreach($group as $item2)
            {
                if($item2->id == $item['fk_usergroup'])
                {
                    $valid=true;
                    break;
                }
            }
            if($valid)
                echo "ideabox.push({id : '".$item['id']."', name : '".addslashes($item['label'])."'});";
        }
    ?>
    if(ideabox.length > 0)
    {
       openIdeaboxWidget();
       $.each(ideabox, function(key, value){
           $('#ideabox_widget_select').append('<option value="'+ value.id +'">'+ value.name +'</option>');
       });
    }
    
    $('#ideabox_widget_cancel').click(function(){
        $('#ideabox_widget_label').val('');
        $('#ideabox_widget_description').val('');
        
    });
    $('#ideabox_widget_valid').click(function(){
        
        var fk_ideabox = $('#ideabox_widget_select').val();
        var label = $('#ideabox_widget_label').val();
        var description = $('#ideabox_widget_description').val();
        
        $.ajax({
            url: "<?php echo dol_buildpath('/ideabox/ideabox.php', 2); ?>",
            method: "POST",
            data: {
                action: 'saveItem',
                label: label,
                description: description,
                fk_ideabox: fk_ideabox,
                fk_user: <?php echo $user->id; ?>
            }
        }).done(function(){
            location.reload();
        });
        /* FIN AJAX */
        
        
    });
    
});