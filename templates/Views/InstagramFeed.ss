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

        <div><b>id:</b> $Data.postData.id</div>

        <% if $Data.postData.children.length %>
          $Data.postData.children
        <% end_if %>
      </li>
    <% end_loop %>
  </ul>
<% else %>
  <p>There are no Instagram posts</p>
<% end_if %>
