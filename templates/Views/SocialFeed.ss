<style type="text/css">

.socialfeed {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-wrap: wrap;
  width: 100%;
}

.socialfeed li {
  display: block;
  width: 100%;
  margin: 20px 0;
}

</style>

<% if Posts %>
  <ul class="socialfeed">
    <% loop Posts %>
      <li>
        <img src="{$Data.postImage}" width="250" alt="Image">
        <div><b>Link:</b> <a href="$Data.postLink" target="_blank">$Data.postLink</a></div>
        <div><b>Date:</b> $Data.postDate</div>
        <div><b>Date ago:</b> $Data.postDateAgo</div>
        <div><b>Text:</b> $Data.postText.LimitCharacters(100)</div>
        <div><b>Type:</b> $Data.postType</div>
        <div><b>Shares:</b> $Data.postCounter(shares)</div>
        <div><b>Likes:</b> $Data.postCounter(likes)</div>
        <div><b>Comments:</b> $Data.postCounter(comments)</div>
      </li>
    <% end_loop %>
  </ul>
<% else %>
  <p>There are no social posts</p>
<% end_if %>
