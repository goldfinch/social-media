<% if FeedType == 'mixed' %>
$SocialFeed.MixedFeed($FeedLimit)
<% else_if FeedType == 'facebook' %>
$SocialFeed.FacebookFeed($FeedLimit)
<% else_if FeedType == 'instagram' %>
$SocialFeed.InstagramFeed($FeedLimit)
<% end_if %>
