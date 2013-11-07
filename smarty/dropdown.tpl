<ul class="level-{$level} {if $level > 0}dropdown-menu{/if}">
    {foreach from=$menu item=entry}
        {if count($entry->children) >0}
            <li class="level-{$level} dropdown{if $level > 0}-submenu{/if}{if $entry->id == $smarty.get.cPath} active{/if}">
                <a href="index.php?cPath={$entry->id}" class="dropdown-toggle disabled" data-toggle="dropdown">
                    {$entry->name}
                    {if $level == 0}
                        {* include the icon for "collapse-down" here *}
                        +
                    }
                    {else}
                        {* include the icon for "expand-right" here *}
                        ->
                    {/if}
                </a>
                {dropdown menu=$entry->children level=$nextLevel}
                {if $level > 0}
                    {if $entry->description|count_characters:true}
                        <span>{$entry->description}</span>
                    {/if}
                {/if}
            </li>

        {else}
            <li class="level-{$level}{if $entry->id == $smarty.get.cPath} active{/if}">
                <a href="index.php?cPath={$entry->id}">{$entry->name}</a>
                {if $level > 0}
                    {if $entry->description|count_characters:true}
                        {$entry->description}
                    {/if}
                {/if}
            </li>
        {/if}
    {/foreach}
</ul>