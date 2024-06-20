<?php



interface PostMetaInterface {
  public function process_incoming(PostMetaUtils $utils): bool;
  public function process_multiple(PostMetaUtils $utils): bool;
  public function process_single(PostMetaUtils $utils): bool;
}
