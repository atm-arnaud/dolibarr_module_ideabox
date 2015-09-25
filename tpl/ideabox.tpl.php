[onshow;block=begin;when [view.type]=='showficheideabox']
    <div class="tabBar" style="margin-top:15px;">
        <table width="100%" class="border workstation">
            <tr style="background-color:#dedede;">
                <td colspan="4">&nbsp;&nbsp;<b>Gestion d'une boîte</b></td>
            </tr>
                [onshow;block=begin;when [TIdeabox.id] != 0]
            <tr>
                <td colspan="1">&nbsp;&nbsp;numéro</td>
                <td colspan="3" align="center">&nbsp;&nbsp;[TIdeabox.id;strconv=no]</td>
            </tr>
                [onshow;block=end]
            <tr>
                <td colspan="1" width="20%">&nbsp;&nbsp;Label</td>
                <td colspan="3" align="center">&nbsp;&nbsp;[TIdeabox.label;strconv=no]</td>
            </tr>
            <tr>
                <td colspan="1">&nbsp;&nbsp;Groupe d'utilisateurs</td>
                [onshow;block=begin;when [view.mode]=='view']
                <td colspan="3" align="center">&nbsp;&nbsp;[TIdeabox.usergroup_trad;strconv=no]</td>
                [onshow;block=end]
                [onshow;block=begin;when [view.mode]=='edit']
                <td colspan="3" align="center">&nbsp;&nbsp;[TIdeabox.usergroup;strconv=no]</td>
                [onshow;block=end]
            </tr>
            [onshow;block=begin;when [TIdeabox.id] != 0]
            <tr>
                <td></td>
            </tr>
            <tr style="background-color:#dedede;">
                <td colspan="4">&nbsp;&nbsp;<b>Liste des idées proposées</b></td>
            </tr>
            <tr style="background-color:#eee;">
                <th align="left">&nbsp;&nbsp;Label</th>
                <th align="center" width="60%">Description</th>
                <th align="center" width="15%">Utilisateur</th>
                <th width="5%" class="draftedit">Supprimer&nbsp;&nbsp;</th>
            </tr>
            <tr style="background-color:#fff;">
                <td align="left">&nbsp;&nbsp;[TIdeaboxItem.label;strconv=no;block=tr]</td>
                <td align="center">[TIdeaboxItem.description;strconv=no;block=tr]</td>
                <td align="center">[TIdeaboxItem.fk_user_trad;strconv=no;block=tr]</td>
                <td align='center' class="draftedit">[TIdeaboxItem.delete;strconv=no;block=tr]</td>
            </tr>
            <tr>
                <td colspan="4" align="center">[TIdeaboxItem;block=tr;nodata]Aucune proposition disponible</td>
            </tr>
            [onshow;block=end]
        </table>
    </div>
    <div class="tabsAction">
        [onshow;block=begin;when [view.user_right]=='1']
        [onshow;block=begin;when [view.mode]!='edit']
        <div class="inline-block divButAction">
            <a href="?id=[TIdeabox.id]&action=edit" class="butAction">Modifier</a>
        </div>
        <div class="inline-block divButAction">
            <a class="butActionDelete" href="[view.url]?id=[TIdeabox.id]&action=delete">Supprimer</a>
        </div>
        [onshow;block=end]
        [onshow;block=end]
        [onshow;block=begin;when [view.mode]=='edit']
        <div class="inline-block divButAction">
            <input type="submit" value="Enregistrer" name="save" class="button">
        </div>
        <div class="inline-block divButAction">
            <a style="font-weight:normal;text-decoration:none;" class="button"  href="[view.url]?id=[TIdeabox.id]&action=view">Annuler</a>
        </div>
        [onshow;block=end]
[onshow;block=end]


