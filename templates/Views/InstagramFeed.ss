<% if InstagramPosts($limit) %>
<div class="container">
  <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
    <% loop InstagramPosts($limit) %>
    <div class="col">
      <div class="card">
        <img
          class="card-img-top"
          src="{$Data.postImage}"
          height="250"
          style="object-fit: cover"
          alt="$Data.postText.LimitCharacters(32)"
        />
        <div class="card-body">
          <div>
            <b>Link:</b>
            <a href="$Data.postLink" rel="noreferrer noopener" target="_blank">$Data.postLink</a>
          </div>
          <div><b>Date:</b> $Data.postDate</div>
          <div><b>Date ago:</b> $Data.postDateAgo</div>
          <div><b>Text:</b> $Data.postText.LimitCharacters(100)</div>
          <div><b>Type:</b> $Data.postType</div>

          <div><b>id:</b> $Data.postData.id</div>

          <% if $Data.postData.children.length %>$Data.postData.children<% end_if %>
        </div>
      </div>
    </div>
    <% end_loop %>
  </div>
</div>
<% else %>
<div class="container">
  <p>There are no Instagram posts</p>
</div>
<% end_if %>
