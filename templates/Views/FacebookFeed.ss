<% if FacebookPosts($limit) %>
<div class="container">
  <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
    <% loop FacebookPosts($limit) %>
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
          <div><b>Date:</b> $Data.postDate(Y-m-d H:i:s)</div>
          <div><b>Date ago:</b> $Data.postDateAgo</div>
          <div><b>Text:</b> $Data.postText.LimitCharacters(100)</div>
          <div><b>Shares:</b> $Data.postCounter(shares)</div>
          <div><b>Likes:</b> $Data.postCounter(likes)</div>
          <div><b>Comments:</b> $Data.postCounter(comments)</div>
          <div><b>Type:</b> $Data.postType</div>

          <%--
          <div><b>likes:</b> $Data.postData.likes</div>
          --%>

          <div><b>id:</b> $Data.postData.id</div>
          <% if Data.postData.icon %>
          <div><b>icon:</b> <img src="$Data.postData.icon" alt="Icon" /></div>
          <% end_if %>
          <%--
          <div><b>story:</b> $Data.postData.story</div>
          <div><b>story_tags:</b> $Data.postData.story_tags</div>
          <div><b>sharedposts:</b> $Data.postData.sharedposts</div>
          <div><b>isharesd:</b> $Data.postData.isharesd</div>
          <div><b>is_hiddenID:</b> $Data.postData.is_hidden</div>
          <div><b>event:</b> $Data.postData.event</div>
          --%>
          <% if $Data.postTags %>
          <div><b>message_tags:</b> <% loop $Data.postTags %>$name<% end_loop %></div>
          <% end_if %>
        </div>
      </div>
    </div>
    <% end_loop %>
  </div>
</div>
<% else %>
<div class="container">
  <p>There are no Facebook posts</p>
</div>
<% end_if %>
