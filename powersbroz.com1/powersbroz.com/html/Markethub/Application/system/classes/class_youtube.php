<?php
class youtube
{
    var $data = '';
    var $id = '';

    public function youtube($id)
    {
        if (strlen($id) >= 22) {
            parse_str( parse_url( $id, PHP_URL_QUERY ) );
            $this->id = $v;
        } else {
            $this->id=$id;
        }

        $url = "http://gdata.youtube.com/feeds/videos/".$this->id;
        $browser_id = "none";
        $curl_handle = curl_init();
        $options = array
        (
            CURLOPT_URL=>$url,
            CURLOPT_HEADER=>false,
            CURLOPT_RETURNTRANSFER=>true,
            CURLOPT_USERAGENT=>$browser_id
        );
        curl_setopt_array($curl_handle,$options);
        $server_output = curl_exec($curl_handle);
        curl_close($curl_handle);

        $this->data=$server_output;
    }

    public function getTitle()
    {
        $startString = "<media:title type='plain'>";
        $endString = "</media:title>";

        $tempString = strstr($this->data, $startString);

        $endLocation = strpos($tempString, $endString);
        $title = substr($tempString, 0, $endLocation);

        if (empty($title)) {
            $title=false;
        } else {
            $title=substr($title,strlen($startString));
        }

        return $title;
    }

    public function getDescription()
    {
        $startString = "<media:description type='plain'>";
        $endString = "</media:description>";

        $starLocation = strpos($this->data, $startString);
        $tempString = substr($this->data, $starLocation);

        $endLocation = strpos($tempString, $endString);
        $description = substr($tempString, 0, $endLocation);

        if (empty($description)) {
            $description=false;
        } else {
            $description=substr($description,strlen($startString));
        }

        return $description;
    }

    public function getUrl()
    {
        return "http://www.youtube.com/watch?v=".$this->id;
    }

    public function getUrlImage($option)
    {
        if($option=='default') {
            return 'http://i.ytimg.com/vi/'.$this->id.'/default.jpg';
        }
        if($option=='grande') {
            return 'http://i.ytimg.com/vi/'.$this->id.'/0.jpg';
        }
        if($option==1) {
            return 'http://i.ytimg.com/vi/'.$this->id.'/1.jpg';
        }
        if($option==2) {
            return 'http://i.ytimg.com/vi/'.$this->id.'/2.jpg';
        }
        if($option==3) {
            return 'http://i.ytimg.com/vi/'.$this->id.'/3.jpg';
        }
    }

}
?>