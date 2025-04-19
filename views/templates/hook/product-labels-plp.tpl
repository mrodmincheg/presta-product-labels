{if $labels}
  <div class="product-labels">
    {foreach $labels as $label}
      <span class="product-label" style="background-color:{$label->getColor()};">
      </span>
    {/foreach}
  </div>
{/if}