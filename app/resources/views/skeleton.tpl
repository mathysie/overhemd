<!DOCTYPE html>
<html lang="nl">
    <head>
        <title>{block title}FooBar{/block} - Overhemd</title>
        {include '_head.tpl'}

        {block head}{/block}
    </head>
    <body>
        <header class="container">
        {capture 'active'}{block active}{/block}{/capture}
            <nav class="navbar navbar-expand-md navbar-light bg-light border fixed-top">
                <a class="navbar-brand" href="/">
                    <img src="/assets/img/logo.svg" width="30" height="30">
                    &nbsp;Overhemd
                </a>
                <span class="badge badge-pill badge-danger mr-2 px-2 py-1">Debug</span>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarDropdown" aria-controls="navbarDropdown" aria-expanded="false">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarDropdown">
                    <div class="navbar-nav">
                        <a class="nav-item nav-link {if $smarty.capture.active=='tapschema'}active{/if}" href="{route route='tapschema.tapmail'}">Tapschema</a>
                        <a class="nav-item nav-link {if $smarty.capture.active=='calendar'}active{/if}" href="{route route='calendar.overzicht'}">Calendar</a>
                    </div>
                </div>
            </nav>
        </header>
        <main class="container pt-3 my-5">
            {block content}
            {/block}
        </main>
        <footer class="footer fixed-bottom">
            {capture 'actions_left'}{block actions_left}{/block}{/capture}
            {capture 'actions_right'}{block actions_right}{/block}{/capture}

            {if !empty($smarty.capture.actions_left) || !empty($smarty.capture.actions_right)}
            <div class="container">
                <span class="actions pull-left">{$smarty.capture.actions_left}</span>
                <span class="actions pull-right">{$smarty.capture.actions_right}</span>
            </div>
            {/if}
        </footer>

        {include '_scripts.tpl'}
        {block scripts}{/block}
    </body>
</html>
