<?php

class IndexController extends My_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        parent::init();
        Zend_Layout::startMvc();
        $this->_helper->layout()->setLayout('layout', 'default');
    }

    public function listAction() {
        //$this->view->title = "Aktualności";
        //$this->view->mainTitle = "Prawo jazdy Rzeszów - OSK AUTO TURBO";
        # We don't want to render Layout
        //  $this->_helper->layout()->disableLayout();
        # rendering view is also not necessary
        //$this->_helper->viewRenderer->setNoRender();

        $req = $this->getRequest();

        # Pagination parameters

        $currentPage = $req->get('page', 1);
        $resultsPerPage = $req->getPost('rp', 6);

        # Sorting parameters
        $orderBy = $req->getPost('sortname', 'date');
        $orderType = $req->getPost('sortorder', 'DESC');

        # Query parameters - for Searching
        $query = $req->getPost('query', '');
        $qtype = $req->getPost('qtype', 'date');

        # Creating Doctine pager object

        $pager = new Doctrine_Pager(
                                Doctrine_Query::create()
                                ->from('Application_Model_Article a')
                                ->orderby("$orderBy $orderType"),
                        $currentPage, // Current page of request
                        $resultsPerPage // (Optional) Number of results per page. Default is 10
        );

        $items = $pager->execute();


        $this->view->currentPage = $currentPage;
        $this->view->pagesCount = ceil($pager->getNumResults() / $pager->getMaxPerPage());

        if ($this->view->currentPage > $this->view->pagesCount) {
            throw new Zend_Controller_Action_Exception('Strona nie istnieje', 404);
        }
        $elements = array();
        # We also have to format our data array that flexifrid could retrieve itt


        foreach ($items as $item) {

            $text = $item->content;
            if (strlen($text) > 600) {
                $text = substr($text, 0, 600) . '...';
            }
            $elements[] = array(
                'id' => $item->id,
                'title' => $item->title,
                'date' => $item->date,
                'content' => $text,
                'slug' => $item->slug,
                    // 'cell' => array_values($item->toArray())
            );
        }

        $this->view->articles = $elements;
        /*   echo Zend_Json::encode(array(
          'page' => $currentPage,
          'total' => $pager->getNumResults(),
          'rows' => $elements
          )); */
    }

    public function indexAction() {
        // action body
        if ($this->getRequest()->getRequestUri() == '/public/') {
            throw new Zend_Controller_Action_Exception('Strona nie istnieje', 404);
        }

        //$this->view->title = "Aktualności";
        $this->view->mainTitle = "Prawo jazdy Rzeszów - OSK AUTO TURBO";
        $this->view->articles = Doctrine_Query::create()
                ->from('Application_Model_Article a')
                ->orderBy('a.date DESC')
                ->execute();

        foreach ($this->view->articles as $article) {
            $text = '';
            if (strlen($article->content) > 600) {
                $text = substr($article->content, 0, 600) . '...';
            } else {
                $text = $article->content;
            }

            $article->content = $text;
        }
    }

    public function onasAction() {
        // action body
        $this->view->title = "O nas";
        $this->view->mainTitle = "O Nas - Ośrodek szkolenia kierowców Rzeszów - OSK AUTO TURBO";
    }

    public function szkolenieAction() {
        // action body
        $this->view->title = "Szkolenie";
        $this->view->mainTitle = "Szkolenie - nauka jazdy Rzeszów - OSK AUTO TURBO";
    }

    public function zapisynakursAction() {

        $this->view->mainTitle = "Zapisy - nauka jazdy Rzeszów - OSK AUTO TURBO";
		$this->view->mainTitle = "Zapisy - OSK AUTO TURBO";

        $form = new Form_ZapisyOnline();
        $rodzaj_kursu = $form->getElement('rodzaj_kursu');
        $rodzaj_kursu->addMultiOptions(My_CourseTypes::getAllCourseTypes());
        $postData = array();
        $formData = array();
        $data = array();
        $data['imie'] = '';
        $data['nazwisko'] = '';
        $data['email'] = '';
        $data['telefon'] = '';
        $data['uwagi'] = '';
        $flag = true;
        // czy formularz zostal wyslany
        if ($this->_request->isPost()) {
            // pobranie danych post
            $postData = $this->_request->getPost();
			var_dump($postData);
            if (isset($postData['txt_captcha']['id'])) {
                $captcha = $postData['txt_captcha']['id'];
            }

            // walidacja danych post
            if ($form->isValid($postData)) {
                echo "ALA1<br/>";
                // pobranie danych wyslanych z formularza
                $formData = $form->getValues();
                $student = new Application_Model_Student();
                $email = Doctrine_Query::create()
                        ->from('Application_Model_Student s')
                        ->where('s.email = ?', $formData['email'])
                        ->fetchArray();
				echo "ALA2<br/>";

					echo "ALA4<br/>";
                    $formData['created'] = new Doctrine_Expression('NOW()');
                    $formData['registered'] = new Doctrine_Exception('FALSE');
                    $student->add($formData);
					echo "ALA5<br/>";
                    $nameContentMail = My_CourseTypes::getMailContentNameByCourseTypeId($formData['rodzaj_kursu']);

                    $mail = new Zend_Mail('UTF-8');
					
                    $body = $this->view->partial($nameContentMail, array(
                        'imie' => $student->imie,
                        'nazwisko' => $student->nazwisko,
                        'includeStyles' => true
                            )
                    );
                    $subject = My_CourseTypes::getMailTitleById($formData['rodzaj_kursu']);
                    $mail->addTo($student->email)
                            ->setSubject($subject)
                            ->setBodyHtml($body);
					$mail->setFrom('oskautoturbo@gmail.com', 'OSK AUTO TURBO');		
					try {
                    $mail->send();
					} catch (Exception $e) {
						echo "<pre>Błąd przy wysyłce maila: " . $e->getMessage() . "</pre>";
					}
					
                    $flag = false;
                
				echo "ALA6<br/>";

                // operacje na danych
                //Zend_Debug::dump($formData);
            }  else {

				$errors = $form->getMessages();

				$errorMessages = '';
				foreach ($errors as $fieldErrors) {
					foreach ($fieldErrors as $msg) {
						$errorMessages .= $msg . '<br>';
					}
				}
				
				$dialogMessage['title'] = $errorMessages;
				$dialogMessage['text'] = array();
				$this->view->dialogMessage = $dialogMessage;
			}		
        }
        // uzupelnij formularz domyslnymi danymi
        else {
            // jest to tylko przyklad. nie jest wymagane uzupelnianie
            // formularza domyslnymi wartosciami
            //$postData['firstname'] = 'Jan';
        }

        if ($flag) {

            $form->populate($postData);
        } else {
            $form->populate($data);
            $dialogMessage['title'] = 'Otrzymaliśmy Twoją wiadomość. </br>Skontaktujemy się jak najszybciej w celu ustalenia szczegółów. </br></br> Twoje dane, które otrzymaliśmy:';
            $dialogMessage['text'] = array();
            $dialogMessage['text']['Imię:'] = $formData['imie'];
            $dialogMessage['text']['Nazwisko:'] = $formData['nazwisko'];
            $dialogMessage['text']['E-mail:'] = $formData['email'];
            $dialogMessage['text']['Telefon:'] = $formData['telefon'];
            $this->view->dialogMessage = $dialogMessage;
        }
        $this->view->form = $form;
        // uzupelnienie formularza danymi
    }

    public function egzaminAction() {
        // action body
        throw new Zend_Controller_Action_Exception('Strona nie istnieje', 404);
        $this->view->title = "Egzamin";
    }

    public function cennikAction() {
        // action body
        $this->view->title = "Cennik";
        $this->view->mainTitle = "Cennik - Prawo jazdy Rzeszów - OSK AUTO TURBO";
    }

    public function promocjeAction() {
        // action body
        $this->view->title = "Promocje";
        $this->view->mainTitle = "Promocje - Prawo jazdy Rzeszów - OSK AUTO TURBO";
    }

    public function bazawiedzyAction() {
        // action body
        //$this->view->title = "Baza wiedzy";
        $this->view->mainTitle = "Baza wiedzy - OSK AUTO TURBO";
    }
	
	    public function opinieAction() {
        // action body
        $this->view->mainTitle = "Opinie - OSK AUTO TURBO";
    }
	
	    public function cennikkursyAction() {
        // action body
        $this->view->mainTitle = "Cennik - OSK AUTO TURBO";
    }

	    public function cennikjazdyAction() {
        // action body
        $this->view->mainTitle = "Cennik - OSK AUTO TURBO";
    }
	
	public function cennikpromocjeAction() {
        // action body
        $this->view->mainTitle = "Cennik - OSK AUTO TURBO";
    }

	public function zapisyjakrozpoczacAction() {
        // action body
        $this->view->mainTitle = "Jak rozpocząć? - OSK AUTO TURBO";
    }
	
	public function szkolenieteoretyczneAction() {
        // action body
        $this->view->mainTitle = "Szkolenie teoretyczne - OSK AUTO TURBO";
    }
	
		public function szkoleniepraktyczneAction() {
        // action body
        $this->view->mainTitle = "Szkolenie praktyczne - OSK AUTO TURBO";
    }
	
		public function szkolenieinstruktorzyAction() {
        // action body
        $this->view->mainTitle = "Nasi instruktorzy - OSK AUTO TURBO";
    }

    public function filmyAction() {
        // action body
        //$this->view->title = "Filmy";
        $this->view->mainTitle = "Filmy - OSK AUTO TURBO";
    }

    public function konkursAction() {
        // action body
        $this->view->title = "Konkurs";


        $form = new Form_ZapisyOnline();

        $postData = array();
        $flag = true;
        $formData = array();

        $data = array();
        $data['imie'] = '';
        $data['nazwisko'] = '';
        $data['email'] = '';
        $data['telefon'] = '';
        $data['uwagi'] = '';
        // czy formularz zostal wyslany
        if ($this->_request->isPost()) {
            // pobranie danych post
            $postData = $this->_request->getPost();

            // walidacja danych post
            if ($form->isValid($postData)) {
                // pobranie danych wyslanych z formularza
                $formData = $form->getValues();
                $email = Doctrine_Query::create()
                        ->from('Application_Model_QuizUser u')
                        ->where('u.email = ?', $formData['email'])
                        ->fetchArray();

                if (count($email) != 0) {
                    $form->email->addError('Osoba o podanym e-mail już istnieje');
                } else {
                    $quizuser = new Application_Model_QuizUser();

                    $quizuser->imie = $formData['imie'];
                    $quizuser->nazwisko = $formData['nazwisko'];
                    $quizuser->email = $formData['email'];
                    $quizuser->telefon = $formData['telefon'];
                    $quizuser->uwagi = $formData['uwagi'];
                    $quizuser->created = new Doctrine_Expression('NOW()');
                    $quizuser->registered = new Doctrine_Exception('FALSE');
                    $quizuser->save();
                    $mail = new Zend_Mail('UTF-8');
                    $mail->addTo($quizuser->email)
                            ->setSubject('Zapis na konkurs')
                            ->setBodyHtml(
                                    $this->view->partial('partials/konkursMail.phtml', array('imie' => $quizuser->imie, 'nazwisko' => $quizuser->nazwisko)
                                    ));
                    $mail->send();
                    $flag = false;
                }
                // operacje na danych
                //  Zend_Debug::dump($formData);
            }
        }
        // uzupelnij formularz domyslnymi danymi
        else {
            
        }
        if ($flag) {
            $form->populate($postData);
        } else {

            $form->populate($data);
            $dialogMessage['title'] = 'Zostałeś zapisany na konkurs';
            $dialogMessage['text'] = array();
            $dialogMessage['text']['imię'] = $formData['imie'];
            $dialogMessage['text']['nazwisko'] = $formData['nazwisko'];
            $dialogMessage['text']['e-mail'] = $formData['email'];
            $dialogMessage['text']['telefon'] = $formData['telefon'];
            $this->view->dialogMessage = $dialogMessage;
        }


        $this->view->form = $form;


        // uzupelnienie formularza danymi
        //$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }

    public function galeriaAction() {
        // action body
        //$this->view->title = "Galeria";
		$this->view->mainTitle = "Galeria - OSK AUTO TURBO";
        //$this->view->path_img = '/img/gallery/';
        //$this->view->path_mini = $this->view->path_img . 'mini/';
        //My_StaticLibrary::generateGalery('.' . $this->view->path_img, '.' . //$this->view->path_mini);
        //$images = array();
        //$dir = new DirectoryIterator('.' . $this->view->path_img);

        //foreach ($dir as $file) {
            // Pomiń pozycje "." oraz ".."
        //    if ($file->isDot()) {
        //        continue;
        //    }
        //    if (!$file->isDir()) {

        //        $name_roz = explode('.', $file);
        //        $min_name = $name_roz[0] . '.th.' . $name_roz[1];

         //       if (file_exists('.' . $this->view->path_mini . $min_name)) {

        //            $images[$this->view->path_img . $file->__toString()]['mini'] = $this->view->path_mini . $min_name;
         //           $images[$this->view->path_img . $file->__toString()]['title'] = $name_roz[0];
        //        }
        //        else
        //            continue;
        //    }
        //}
        //$this->view->images = $images;
    }

    public function karieraAction() {
        // action body
        $this->view->title = "Oferty pracy";
        $this->view->mainTitle = "Kariera - Ośrodek Szkolenia Kierowców AUTO TURBO";
    } 
     
    public function kontaktAction() {
        // action body
        $this->view->mainTitle = "Kontakt - OSK AUTO TURBO";
    }
	
	public function galerianowaAction() {
        // action body
        $this->view->mainTitle = "Galeria - OSK AUTO TURBO";
    }

    public function newsAction() {
        //$this->view->title = "Aktualności";
        $this->view->mainTitle = "Prawo jazdy Rzeszów - OSK AUTO TURBO";
        $filter = new Zend_Filter_StripTags();
        $slug = $filter->filter($this->getRequest()->getParam('slug'));

        $articles = Doctrine_Query::create()
                ->from('Application_Model_Article a')
                ->where('a.slug = ?', $slug)
                ->execute();

        if ($articles->count() != 0) {
            $this->view->article = $articles[0];
        } else {
            $this->_redirect('/');
        }
    }

    public function captchaAction() {
        $request = $this->getRequest();
        // Get out from the $_POST array the captcha part...  
        $captcha = $request->getPost('captcha');
        // Actually it's an array, so both the ID and the submitted word  
        // is in it with the corresponding keys  
        // So here's the ID...  
        $captchaId = $captcha['id'];
        // And here's the user submitted word...  
        $captchaInput = $captcha['input'];
        // We are accessing the session with the corresponding namespace  
        // Try overwriting this, hah!  
        $captchaSession = new Zend_Session_Namespace('Zend_Form_Captcha_' . $captchaId);
        // To access what's inside the session, we need the Iterator  
        // So we get one...  
        $captchaIterator = $captchaSession->getIterator();
        // And here's the correct word which is on the image...  

        $captchaWord = $captchaIterator['word'];
        // Now just compare them...  
        if ($captchaInput == $captchaWord) {
            // OK  
        } else {
            // NOK  
        }
    }

    public function validateformAction() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->getHelper('layout')->disableLayout();
        $f = new Form_ZapisyOnline();
        $f->isValid($this->_getAllParams());
        $json = $f->getMessages();
        header('content-type: application/json');
        echo Zend_Json::encode($json);
    }

    public function downloadAction() {
        $this->_helper->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $filename = $this->_getParam('filename');
        $actionName = $this->_getParam('actionName');
        $bootstrap = $this->getInvokeArg('bootstrap');
        $config = $bootstrap->getOptions();
        $pathPdf = $config['path']['pdf'];
        //Zend_Debug::dump($this->getRequest());
        $file = realpath(APPLICATION_PATH . '/' . $pathPdf . $actionName . '/' . $filename);
        //$roz = end(explode('.', $filename));
        //echo $roz;
        //echo $file;
        $response = $this->getResponse();
        $response->clearHeaders();
        if (!file_exists($file)) {
            $this->_helper->getHelper('layout')->enableLayout();
            $this->_helper->viewRenderer->setRender();

            $this->_redirect('/');
        } else {

            $response->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');
            $response->setHeader('Content-length', filesize($file));
            $response->setHeader('Pragma', 'public');
            $response->setHeader('Content-Type', 'application/' . 'pdf');
            $response->setHeader('Content-Type', 'application/download');
            $response->sendHeaders();
            //echo $file;
            readfile($file);
            die();
        }
    }

    public function sitemapAction() {
        $this->view->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        echo $this->view->navigation()->sitemap();
    }

}
