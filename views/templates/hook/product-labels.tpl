{if $labels}
  <div class="product-labels" style="display: none">
    {foreach $labels as $label}
      <span class="product-label" style="background-color:{$label->getColor()};">
      </span>
    {/foreach}
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const $labelBlock = $('.product-labels');

      if ({$moveToTitle}) {
        const $title = $('h1.h1');

        if ($labelBlock.length && $title.length) {
          $labelBlock.insertBefore($title);
        }
      }

      $labelBlock.toggle();

    });
  </script>
{/if}