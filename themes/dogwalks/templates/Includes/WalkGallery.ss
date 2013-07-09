<% require javascript('themes/dogwalks/javascript/min/jquery.magnific-popup.min.js') %>
<script type="text/javascript" charset="utf-8">
  jQuery(document).ready(function($) {
    $('.image-link').magnificPopup({
      type:'image',
      gallery:{enabled:true}
    });
  });
</script>
<% if $SortedImages %>
  <div id="Gallery" class="noprint">
    <% with $SortedImages.First %>
      <div id="GalleryPreview" style="background: url('{$setWidth(803).CroppedImage(803,267).link}') no-repeat center center">
        <a href="$url" class="image-link">$setWidth(803).CroppedImage(803,267)</a>
      </div>
    <% end_with %>
    <div id="GalleryThumbs">
      <% loop $SortedImages %><% if not $first %><a href="$url" class="image-link">$setHeight(50)</a><% end_if %><% end_loop %>
    </div>
  </div>
<% end_if %>