<?php

namespace ProcessWire;

?>

<footer class="main-footer padding-y-lg bg-primary">
  <div class="container max-width-lg">
    <div class="main-footer__content">
      <div class="main-footer__logo">
        <a href="<?= (string) wire('pages')->get(1)->httpUrl; ?>">
          <img src="<?= wire('config')->urls->templates; ?>assets/static/ich-lerne-online-logo-zusatz.svg" class="footer-logo">
        </a>
      </div>
      
      <nav class="main-footer__nav">
        <ul class="main-footer__nav-list">
          <?php
          foreach($this->navBlocks as $block){
            ?>
            <li class="main-footer__nav-item">
              <h4><?= $block->title; ?></h4>

              <?php 
              foreach($block->linklist as $link){
                if($link->type === 'page_reference'){
                  $href = '';
                  if($link->page_reference->id){
                    $href .= $link->page_reference->url;
                  }
                  if(!empty($link->section_name)){
                    $href .= '#' . $link->section_name;
                  }
                  echo '<div><a href="' . $href . '">' . $link->title . '</a></div>';
                }else if($link->type === 'external_link'){
                  echo '<div><a href="' . $link->link . '">' . $link->title . '</a></div>';
                }
              }
              ?>
            </li>
            <?php
          }
          ?>
        </ul>
      </nav>
    </div>
  
    <div class="main-footer__colophon">
      <div class="main-footer__colophon-nav">
        <h4><span style="color: var(--color-white)">&copy;&nbsp;2020&nbsp;ich-lerne-online.org<small>&nbsp;|&nbsp;Ein&nbsp;Projekt&nbsp;von&nbsp;<a href="https://krisenkultur.de">KrisenKultur</a></small></span></h4>
      </div>

      <div class="main-footer__socials">
          <a href="https://twitter.com/ichlerneonline" target="_blank">
            <svg class="icon" viewBox="0 0 16 16"><title>Folge uns auf Twitter</title><g><path d="M16,3c-0.6,0.3-1.2,0.4-1.9,0.5c0.7-0.4,1.2-1,1.4-1.8c-0.6,0.4-1.3,0.6-2.1,0.8c-0.6-0.6-1.5-1-2.4-1 C9.3,1.5,7.8,3,7.8,4.8c0,0.3,0,0.5,0.1,0.7C5.2,5.4,2.7,4.1,1.1,2.1c-0.3,0.5-0.4,1-0.4,1.7c0,1.1,0.6,2.1,1.5,2.7 c-0.5,0-1-0.2-1.5-0.4c0,0,0,0,0,0c0,1.6,1.1,2.9,2.6,3.2C3,9.4,2.7,9.4,2.4,9.4c-0.2,0-0.4,0-0.6-0.1c0.4,1.3,1.6,2.3,3.1,2.3 c-1.1,0.9-2.5,1.4-4.1,1.4c-0.3,0-0.5,0-0.8,0c1.5,0.9,3.2,1.5,5,1.5c6,0,9.3-5,9.3-9.3c0-0.1,0-0.3,0-0.4C15,4.3,15.6,3.7,16,3z"></path></g></svg>
          </a>
  
          <a href="https://instagram.com/krisenkultur" target="_blank">
            <svg class="icon" viewBox="0 0 32 32">
              <title>Folge uns auf Instagram</title>
              <g>
                <path d="M16,8.3A7.7,7.7,0,1,0,23.7,16,7.7,7.7,0,0,0,16,8.3ZM16,21a5,5,0,1,1,5-5A5,5,0,0,1,16,21Z"></path>
                <path d="M16,3.7c4,0,4.479.015,6.061.087a6.426,6.426,0,0,1,4.51,1.639,6.426,6.426,0,0,1,1.639,4.51C28.282,11.521,28.3,12,28.3,16s-.015,4.479-.087,6.061a6.426,6.426,0,0,1-1.639,4.51,6.425,6.425,0,0,1-4.51,1.639c-1.582.072-2.056.087-6.061.087s-4.479-.015-6.061-.087a6.426,6.426,0,0,1-4.51-1.639,6.425,6.425,0,0,1-1.639-4.51C3.718,20.479,3.7,20.005,3.7,16s.015-4.479.087-6.061a6.426,6.426,0,0,1,1.639-4.51A6.426,6.426,0,0,1,9.939,3.79C11.521,3.718,12,3.7,16,3.7M16,1c-4.073,0-4.584.017-6.185.09a8.974,8.974,0,0,0-6.3,2.427,8.971,8.971,0,0,0-2.427,6.3C1.017,11.416,1,11.927,1,16s.017,4.584.09,6.185a8.974,8.974,0,0,0,2.427,6.3,8.971,8.971,0,0,0,6.3,2.427c1.6.073,2.112.09,6.185.09s4.584-.017,6.185-.09a8.974,8.974,0,0,0,6.3-2.427,8.971,8.971,0,0,0,2.427-6.3c.073-1.6.09-2.112.09-6.185s-.017-4.584-.09-6.185a8.974,8.974,0,0,0-2.427-6.3,8.971,8.971,0,0,0-6.3-2.427C20.584,1.017,20.073,1,16,1Z"></path>
                <circle cx="24.007" cy="7.993" r="1.8"></circle>
              </g>
            </svg>
          </a>
  
          <a href="https://www.facebook.com/Krisenkulturde-112974000338020/" target="_blank">
            <svg class="icon" viewBox="0 0 32 32">
              <title>Folge uns auf Facebook</title><g><path d="M32,16A16,16,0,1,0,13.5,31.806V20.625H9.438V16H13.5V12.475c0-4.01,2.389-6.225,6.043-6.225a24.644,24.644,0,0,1,3.582.312V10.5H21.107A2.312,2.312,0,0,0,18.5,13v3h4.438l-.71,4.625H18.5V31.806A16,16,0,0,0,32,16Z"></path></g>
            </svg>
          </a>
        </div>
    </div>
  </div>
</footer>