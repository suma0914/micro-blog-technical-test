<?php
class BodyClass 
{
	public function getPageBody($uri, $response)
	{
		if(strcasecmp($uri, '/') == 0 || strcasecmp($uri, '/api/posts') == 0)
		{
			$response = "" . $response;
			$response = trim($response);
			$response = substr($response, strpos($response, "["), strpos($response, "]"));
			$output = $output . "<br/>";
			$jsonArray = json_decode($response, true);
			for($i = 0; $i < count($jsonArray); $i++)
			{
				$output = $output . "<div style='background: #4CAF50'><h2><a href='http://micro-blog.dev/api/posts/id/" . $jsonArray[$i]['rowid'] ."'>" . substr($jsonArray[$i]['content'], 0, 25) . "</a></h2><h5><a href='http://micro-blog.dev/api/posts/user/" . $jsonArray[$i]['user_id'] ."'>" . $jsonArray[$i]['user_id'] . "</a></h5><h5>" . $jsonArray[$i]['date'] . "</h5></div><br/>";
			}		
		}
		else if(strcasecmp(substr($uri, strpos($uri, "/"), strrpos($uri, "/")), '/api/posts/user') == 0)
		{
			$response = "" . $response;
			$response = trim($response);
			$response = substr($response, strpos($response, "["), strpos($response, "]"));
			$output = $output . "<br/>";
			$jsonArray = json_decode($response, true);
			for($i = 0; $i < count($jsonArray); $i++)
			{
				$output = $output . "<div style='background: #4CAF50'><h2><a href='http://micro-blog.dev/api/posts/id/" . $jsonArray[$i]['rowid'] ."'>" . $jsonArray[$i]['content'] . "</a></h2><h5><a href='http://micro-blog.dev/api/posts/user/" . $jsonArray[$i]['user_id'] ."'>" . $jsonArray[$i]['user_id'] . "</a></h5><h5>" . $jsonArray[$i]['date'] . "</h5></div><br/>";
			}
		}
		else if(strcasecmp(substr($uri, strpos($uri, "/"), strrpos($uri, "/")), '/api/posts/id') == 0)
		{
			$output = $output . "<div style='background: #4CAF50'><h3>" . $response['content'] . "</h3><h5><a href='http://micro-blog.dev/api/posts/user/" . $response['user_id'] ."'>" . $response['user_id'] . "</a></h5><h5>" . $response['date'] . "</h5></div><br/>";
		}
		else
		{
			$output = "<h2> 404 - Page not found </h2>";
		}
		return $output;
	}
}
?>
