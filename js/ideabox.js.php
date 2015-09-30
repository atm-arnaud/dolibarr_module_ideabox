<?php
    define('INC_FROM_DOLIBAR', true);
    require_once('../config.php');
    dol_include_once('/ideabox/lib/ideabox.lib.php');

    $langs->load('ideabox@ideabox');
    $PDOdb=new TPDOdb;
    $sql = 'SELECT DISTINCT(ib.rowid), ib.label, ib.fk_usergroup 
            FROM (('.MAIN_DB_PREFIX.'usergroup ub RIGHT JOIN '.MAIN_DB_PREFIX.'ideabox ib on ib.fk_usergroup = ub.rowid)
            INNER JOIN '.MAIN_DB_PREFIX.'usergroup_user ubu on ub.rowid = ubu.fk_usergroup)
            WHERE ubu.fk_user = '.$user->id.'
            UNION
            SELECT ib.rowid, ib.label, ib.fk_usergroup 
            FROM '.MAIN_DB_PREFIX.'ideabox ib
            WHERE fk_usergroup = -1';
    $PDOdb->Execute($sql);
?>
$('document').ready(function(){
        
    function openIdeaboxWidget(){
        
        
        
        $ideabox = $('<div id="ideabox_widget"><div class="titre center">Ajouter une idée dans une boîte</div>Boîte : <select id="ideabox_widget_select"></select><br /></div>');
        $ideabox.append('Titre : <br /><input id="ideabox_widget_label" type="text" id="ideabox_widget_label" placeholder="Titre de l\'idée" /><br />');
        $ideabox.append('Description : <br /><textarea id="ideabox_widget_description" cols="10" rows="2" type="text" placeholder="Description de l\'idée" /><br />');
        $ideabox.append('<div class="center" style="margin-top:5px;"><a id="ideabox_widget_valid" class="button" style="cursor:pointer;">Ajouter</a>&nbsp;'
                                + '<a id="ideabox_widget_cancel" class="button" style="cursor:pointer;">Annuler</a></div>');
                                
        $('body').append($ideabox);                                
    }
    
    var ideabox = new Array();
    
    <?php
        while ($PDOdb->Get_line())
        {
            echo "ideabox.push({id : '".$PDOdb->Get_field('rowid')."', name : '".addslashes($PDOdb->Get_field('label'))."'});";
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
        $('#ideabox_widget_label').empty();
        $('#ideabox_widget_description').empty();
        
    });
    $('#ideabox_widget_valid').click(function(){
        
        var fk_ideabox = $('#ideabox_widget_select').val();
        var label = $('#ideabox_widget_label').val();
        var description = $('#ideabox_widget_description').val();
        
        $.ajax({
            url: "<?php echo dol_buildpath('/ideabox/script/interface.php', 2); ?>",
            method: "POST",
            data: {
                put: 'item',
                label: label,
                description: description,
                fk_ideabox: fk_ideabox,
                fk_user: <?php echo $user->id; ?>
            }
        }).done(function(){
                $.jnotify("Bo&icirc;te modifi&eacute;e avec succ&egrave;s",
                    "3000",
                    false,
                    { remove: function (){} } 
                );
              
          
        });
        /* FIN AJAX */
        
        
    });
    
});