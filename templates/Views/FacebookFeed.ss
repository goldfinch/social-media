<% if FacebookPosts %>
  <ul class="socialfeed">
    <% loop FacebookPosts %>
      <li>
        <img src="{$Data.postImage}" width="250" alt="Image">
        <div><b>Link:</b> <a href="$Data.postLink" target="_blank">$Data.postLink</a></div>
        <div><b>Date:</b> $Data.postDate(Y-m-d H:i:s)</div>
        <div><b>Date ago:</b> $Data.postDateAgo</div>
        <div><b>Text:</b> $Data.postText.LimitCharacters(100)</div>
        <div><b>Shares:</b> $Data.postCounter(shares)</div>
        <div><b>Likes:</b> $Data.postCounter(likes)</div>
        <div><b>Comments:</b> $Data.postCounter(comments)</div>
        <div><b>Type:</b> $Data.postType</div>

        <%-- <div><b>likes:</b> $Data.postData.likes</div> --%>

        <div><b>id:</b> $Data.postData.id</div>
        <div><b>icon:</b> <img src="$Data.postData.icon" alt="Icon"></div>

        <%-- <div><b>story:</b> $Data.postData.story</div>
        <div><b>story_tags:</b> $Data.postData.story_tags</div>
        <div><b>sharedposts:</b> $Data.postData.sharedposts</div>
        <div><b>isharesd:</b> $Data.postData.isharesd</div>
        <div><b>is_hiddenID:</b> $Data.postData.is_hidden</div>
        <div><b>event:</b> $Data.postData.event</div> --%>

        <% if $Data.postTags %>
        <div><b>message_tags:</b> <% loop $Data.postTags %>$name<% end_loop %></div>
        <% end_if %>
      </li>
    <% end_loop %>
  </ul>
<% else %>
  <p>There are no Facebook posts</p>
<% end_if %>