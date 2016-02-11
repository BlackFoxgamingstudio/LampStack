<?php
/**
 * @package Entity Project Forum Post Class and Methods
 * @version 1.0
 * @date 23 August 2015
 * @author Travis Coats, Zen Perfect Design
 * @location app/classes/
 *
 */

class ProjectForumPost implements ForumPostInterface {

    use Finder, Access, HTML;

    private     $id;
    private     $post_object;
    protected   $subject;
    protected   $body;
    protected   $author;
    protected   $isreply;
    protected   $replyto;
    protected   $sticky;
    private     $created;
    private     $updated;

    protected   $replies = false;

    private static $table = "project_forum_posts";

    // Constructor functions
    public function __construct($record) {
        $this->id 	        = $record['id'];
        $this->post_object  = Project::find('id', $record['projectid']);
        $this->subject      = $record['postsubject'];
        $this->body         = $record['postbody'];
        $this->author       = User::find('id', $record['author']);
        if ($record['isreply'] == 1) {
            $this->isreply  = true;
        } else {
            $this->isreply  = false;
        }
        if ($record['isreply'] == 1) {
            $this->replyto  = self::find('id', $record['replyto']);
        } else {
            $this->replyto  = false;
        }
        if ($record['sticky'] == 1) {
            $this->sticky   = true;
        } else {
            $this->sticky   = false;
        }
        $this->created      = new DateTime($record['created']);
        $this->updated      = new DateTime($record['updated']);

        $this->build_replies();
    }

    public function object() {
        return $this->post_object;
    }

    public function subject() {
        return htmlspecialchars($this->subject);
    }

    public function body() {
        return nl2br(htmlspecialchars($this->body));
    }

    public function author() {
        return $this->author;
    }

    public function is_reply() {
        return $this->isreply;
    }

    public function is_sticky() {
        return $this->sticky;
    }

    public function created() {
        return $this->created;
    }

    public function updated() {
        return $this->updated;
    }

    public function has_replies() {
        if ($this->replies) {
            return true;
        } else {
            return false;
        }
    }

    public function get_replies() {
        return $this->replies;
    }

    public function build_replies() {
        $replies = gen_query("SELECT * FROM project_forum_posts WHERE isreply = 1 AND replyto = ".$this->id);
        if ($replies['count'] > 0) {
            $this->replies = $replies;
        } else {
            $this->replies = false;
        }
    }

}