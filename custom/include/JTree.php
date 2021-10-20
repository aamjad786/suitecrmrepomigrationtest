<?php
class JTree {
   /**
    * @var UID for the header node 
   */
    private $_head;
 
   /**
    * @var size of list 
    */
    private $_size;
    
   /**
   * @var hash table to store node objects 
   */
    private $_list = array();
 
    /**
     * JTree::__construct()
     * 
     * @return
     */
    public function __construct() {
        $this->_head = $this->createNode('HEAD');
        $this->_size = 0;
    }
 
    /**
    * JTree::getList()
    * 
    * Retreives the hash table
    * 
    * @return array
    */
    public function getTree() {
        return $this->_list;
    }
 
   /**
   * JTree::getNode()
   * Given a UID get the node object
   * 
   * @param mixed $uid
   * @return JNode/Boolean
   */
    public function getNode($uid) {
        if(empty($uid)) {
            throw new Exception('A unique id is required.');
        }
        $ret = false;
      //look for the node in the hash table
      //return false if not found
        if(array_key_exists($uid,$this->_list) === true) {
            $ret = $this->_list[$uid];
        }
        return $ret;
    }
 
   /**
    * JTree::setChild()
    * 
    * This is a helper function. Given a UID for a node
    * set it as the next UID for the node. 
    * 
    * @param mixed $uid
    * @param mixed $childUid
    * @return void
    */
    public function setChild($uid, $childUid) {
        if(empty($uid) || empty($childUid)) {
            throw new Exception('Both a from and a to UIDs are required.');
        }
      //get the node object for this node uid
        $node = $this->getNode($uid);
 
        if($node !== false) {
            $node->setChild($childUid);
        }
    }
 
   /**
    * JTree::setParent()
    * 
    * This is a helper function to set the parent uid
    * 
    * @param mixed $uid - UID of the object to be processed on
    * @param mixed $prevUid - put this as next in the above object
    * @return void
    */
    public function setParent($uid, $parentUid) {
        if(empty($uid) || empty($parentUid)) {
            throw new Exception('Both a from and a to UIDs are required.');
        }
        $node = $this->getNode($uid);
 
        if($node !== false) {
            $node->setParent($parentUid);
        }
    }
 
    /**
     * JTree::createNode()
     * 
    * Create a node, store in hash table
    * return the reference uid
     * @param mixed $value
     * @param mixed $uid
     * @return string $uid
     */
    public function createNode($value, $uid = null) {
        if(!isset($value)) {
            throw new Exception('A value is required to create a node');
        }
 
        $node = new JNode($value, $uid);
        $uid = $node->getUid();
        $this->_list[$uid] = $node;
        return $uid;
    }
 
    /**
     * JTree::addChild()
     * 
     * @param mixed $parentUid
     * @param mixed $childUid
     * @return
     */
    public function addChild($parentUid = null, $childUid) {
        if(empty($childUid)) {
            throw new Exception('A UID for the child is required.');
        }
      //if no parent assume it is the head
        if(empty($parentUid)) {
            $parentUid = $this->_head;
        }
        //parent points to child
        $this->setChild($parentUid, $childUid);
 
        //child points to parent
        $this->setParent($childUid, $parentUid);
 
        return $childUid;
    }
 
    /**
     * JTree::addFirst()
    * Add the first child right after the head
     * 
     * @param mixed $uid
     * @return void
     */
    public function addFirst($uid) {
        if(empty($uid)) {
            throw new Exception('A unique ID is required.');
        }
        $this->addChild($this->_head, $uid);
    }
 
   /**
    * JTree::getChildren()
    * 
    * This is a helper function to get the child node uids given the node uid
    * 
    * @param mixed $uid
    * @return mixed
    */
    public function getChildren($uid) {
        if(empty($uid)) {
            throw new Exception('A unique ID is required.');
        }
 
        $node = $this->getNode($uid);
 
        if($node !== false) {
            return $node->getChildren();
        }
    }
 
   /**
    * JTree::getParent()
    * 
    * This is a helper function to get the 
    * parent node uid
    * 
    * @param mixed $uid
    * @return string $uid
    */
    public function getParent($uid) {
        if(empty($uid)) {
            throw new Exception('A unique ID is required.');
        }
        $ret = false;
      $node = $this->getNode($uid);
 
        if($node !== false) {
            $ret = $node->getParent();
        }
      return $ret;
    }
 
    /**
     * JTree::getValue()
     * 
     * @param mixed $uid
     * @return
     */
    public function getValue($uid) {
        if(empty($uid)) {
            throw new Exception('A unique ID is required.');
        }
 
        $node = $this->getNode($uid);
        return $node->getValue();
    }
}
/**
 * JNode
 * 
 * This is a simple class to construct a node
 * Please note that each node object will be 
 * eventually stored in a hash table where the 
 * hash will be a UID.
 * 
 * Note that in comparison to thee Doubly Linked List implementation
 * the children are now stored in an array
 * 
 * @package JTree   
 * @author Jayesh Wadhwani
 * @copyright Jayesh Wadhwani
 * @version 2011
 */
class JNode {
   /**
    * @var _value for the value field 
   */
    private $_value;
   /**
    * @var _parent uid of the parent node 
   */
    private $_parent;
   /**
    * @var _children collection of uids for the child nodes 
   */
    private $_children = array();
   /**
    * @var _uid for this node 
   */
    private $_uid;
 
    /**
     * JNode::__construct()
     * 
     * @param mixed $value
     * @param mixed $uid
     * @return void
     */
    public function __construct($value = null,$uid = null) {
        if(!isset($value)) {
            throw new Exception('A value is required to create a node');
        }
        $this->setValue($value);
        $this->setUid($uid);
    }
 
    /**
     * JNode::setUid()
     * 
     * @param mixed $uid
     * @return
     */
    public function setUid($uid = null) {
        //if uid not supplied...generate
        if(empty($uid)) {
            $this->_uid = uniqid();
        } else {
            $this->_uid = $uid;
        }
    }
 
    /**
     * JNode::getUid()
     * 
     * @return string uid
     */
    public function getUid() {
        return $this->_uid;
    }
 
    /**
     * JNode::setValue()
     * 
     * @param mixed $value
     * @return void
     */
    public function setValue($value) {
        $this->_value = $value;
    }
 
    /**
     * JNode::getValue()
     * 
     * @return mixed
     */
    public function getValue() {
        return $this->_value;
    }
 
    /**
     * JNode::getParent()
     * 
    * gets the uid of the parent node
    * 
     * @return string uid
     */
    public function getParent() {
        return $this->_parent;
    }
 
    /**
     * JNode::setParent()
     * 
     * @param mixed $parent
     * @return void
     */
    public function setParent($parent) {
        $this->_parent = $parent;
    }
 
    /**
     * JNode::getChildren()
     * 
     * @return mixed
     */
    public function getChildren() {
        return $this->_children;
    }
 
    /**
     * JNode::setChild()
     * 
    * A child node's uid is added to the childrens array
    * 
     * @param mixed $child
     * @return void
     */
    public function setChild($child) {
        if(!empty($child)) {
            $this->_children[] = $child;
        }
    }
 
    /**
     * JNode::anyChildren()
     * 
    * Checks if there are any children 
    * returns ture if it does, false otherwise
    * 
     * @return bool
     */
    public function anyChildren() {
        $ret = false;
 
        if(count($this->_children) > 0) {
            $ret = true;
        }
        return $ret;
    }
 
    /**
     * JNode::childrenCount()
     * 
    * returns the number of children
    * 
     * @return bool/int
     */
    public function childrenCount() {
      $ret = false;
     if(is_array($this->_children)){
      $ret = count($this->_children);
     }
     return $ret;
    }
}
