<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'third_party/google-api-php-client/vendor/autoload.php';
require_once APPPATH.'third_party/google-api-php-client/src/Google/Client.php';

class BaseController extends CI_Controller {

  // This method is used to determine which shell to use
  protected function getAppShellModel($pageId) {
    return 'appshell/HeaderFooterShell';
  }

  protected function getDocument($pageId, $pageData = null) {
    $this->load->model('document/StandardDocument', 'document');

    $this->load->model($this->getAppShellModel($pageId), 'appshell');
    $this->document->setAppShell($this->appshell);

    $page = $this->generatePage($pageId, $this->appshell, $pageData);
    $page->setId($pageId);
    $this->document->setPage($page);

    return $this->document;
  }

  protected function render($document) {
    $output = $this->input->get('output', TRUE);
    if ($output == FALSE) {
      $this->load->view('documents/standard/standard-document', array(
        'appshell' => $document->getAppShell(),
        'page' => $document->getPage()
      ));
      return;
    }
    switch ($output) {
    case 'remote_css':
      $this->renderRemoteCSS($document);
      break;
    case 'partial':
      $this->renderPartial($document);
      break;
    default:
      show_404();
      break;
    }
  }

  protected function renderRemoteCSS($document) {
    $section = $this->input->get('section', TRUE);
    switch ($section) {
    case 'both':
      $this->load->view('output-types/css', array(
        'appshell' => array(
          'stylesheets' => $document->getAppShell()->getRemoteStylesheets(),
          'rawCSS' => $document->getAppShell()->getRemoteRawCSS()
        ),
        'page' => array(
          'stylesheets' => $document->getPage()->getRemoteStylesheets(),
          'rawCSS' => $document->getPage()->getRemoteRawCSS()
        )
      ));
      break;
    case 'shell':
      $this->load->view('output-types/css', array(
        'appshell' => array(
          'stylesheets' => $document->getAppShell()->getRemoteStylesheets(),
          'rawCSS' => $document->getAppShell()->getRemoteRawCSS()
        )
      ));
      break;
    case 'page':
      $this->load->view('output-types/css', array(
        'page' => array(
          'stylesheets' => $document->getPage()->getRemoteStylesheets(),
          'rawCSS' => $document->getPage()->getRemoteRawCSS()
        )
      ));
      break;
    default:
      show_404();
      break;
    }
  }

  protected function renderPartial($document) {
    $section = $this->input->get('section', TRUE);
    $jsonArray = array();
    switch ($section) {
      case 'page':
        $jsonArray['page'] = array(
          'title' => $document->getPage()->getTitle(),
          'html' => $document->getPage()->loadView(true),
          'css' => array(
            'inline' => $document->getPage()->getAllStylesAsCSS(),
            'remote' => $document->getRemoteStyleURL()
          )
        );
        break;
      case 'shell':
        $jsonArray['appshell'] = array(
          'id' => $document->getAppShell()->getAppShellId(),
          'html' => $document->getAppShell()->loadView(true),
          'css' => array(
            'inline' => $document->getAppShell()->getAllStylesAsCSS()
          )
        );
        break;
      default:
        show_404();
        break;
    };
    $this->load->view('output-types/json', array(
      'json' => $jsonArray
    ));
  }
}