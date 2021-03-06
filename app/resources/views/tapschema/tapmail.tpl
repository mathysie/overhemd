{extends file='base.tpl'}

{block active}tapschema{/block}

{block title}Tapschema{/block}

{block breadcrumbs}
<li class="breadcrumb-item active" aria-current="page">Tapschema</li>
{/block}

{block scripts append}
<script type="text/javascript" src="/assets/tinymce/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="/assets/js/tapschema/tapmail.js"></script>
{/block}

{block pagetitle}<h1>Tapschema</h1>{/block}

{block content append}
<p class="lead">Tapschema van {$start|escape} t/m {$end|escape}.</p>
<div class="btn-group" role="group" aria-label="Navigatie">
    <a class="btn btn-success" href="{route route='tapschema.tapmail' offset=$eerder}">
        <span class="far fa-arrow-alt-circle-left"></span>&nbsp;Eén week eerder
    </a>
    <a class="btn btn-success" href="{route route='tapschema.tapmail' offset=$later}">
    <span class="far fa-arrow-alt-circle-right"></span>&nbsp;Eén week later
    </a>
</div>
<h2>Onderwerp</h2>
<textarea class="w-100" rows="1" readonly>Tapschema t/m {$end->formatOnderwerp()|escape}</textarea>
<h2>Inhoud</h2>
<textarea id="tapmail">{$tapmail}</textarea>
{/block}
