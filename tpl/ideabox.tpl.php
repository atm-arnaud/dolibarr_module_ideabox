[onshow;block=begin;when [view.type]=='showficheideabox']
    <div class="tabBar" style="margin-top:15px;">
        <table width="100%" class="border workstation">
            <tr height="40px;">
                <td colspan="3">&nbsp;&nbsp;<b>Contrôle à ajouter</b></td>
            </tr>
            <tr style="background-color:#dedede;">
                <th align="left" width="50%">&nbsp;&nbsp;Libellé du contrôle</th>
                <th align="center" width="20%">Type</th>
                <th width="5%" class="draftedit">Supprimer</th>
            </tr>
            <tr id="WS[workstation.id]" style="background-color:#fff;">
                <td align="left">&nbsp;&nbsp;[TIdeaboxItem.label;strconv=no;block=tr]</td>
                <td align="center">[TIdeaboxItem.description;strconv=no;block=tr]</td>
                <td align='center' class="draftedit">[TIdeaboxItem.action;strconv=no;block=tr]</td>
            </tr>
            <tr>
                <td colspan="4" align="center">[TIdeaboxItem;block=tr;nodata]Aucun contrôle disponible</td>
            </tr>
        </table>
    </div>
        [onshow;block=begin;when [view.mode]!='edit']
            <a href="?id=[co.id]&action=edit" class="butAction">Modifier</a>
            <a class="butActionDelete" href="control.php?id=[co.id]&action=delete">Supprimer</a>
        [onshow;block=end]
        [onshow;block=begin;when [view.mode]=='edit']
            <input type="submit" value="Enregistrer" name="save" class="button">
            <a style="font-weight:normal;text-decoration:none;" class="button"  href="[view.url]?id=[co.id]&action=view">Annuler</a>
        [onshow;block=end]
[onshow;block=end]


