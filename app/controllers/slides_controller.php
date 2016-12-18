<?php
  class SlideController {
  	
  	/**
  	 * RETRIEVES ALL THE SLIDES IN THE DATABASE AND PASSES THEM TO THE VIEW
     * HTTP Request: GET
     * URI template: /slides 
  	 */
    public function index() {
      $slides = Slide::all();		// use the model to get all the slides
      SlideView::index($slides);    // call the view
    }

    
    
    /**
     * DISPLAY A FORM FOR EDITING SLIDE OPTIONS
     * HTTP Request: GET
     * URI Template: /slides/edit/{id}
     */
    public function edit() {
      if (!isset($_GET['id'])) return call('pages', 'error');		// ensure that the ID is included in the request

      $slide = Slide::get(intval($_GET['id']));		// validate the input and call the Slide model to pull up the specific slide
      SlideView::display_slide_options($slide);   // call the view
    }


    /**
     * DISPLAY A FORM TO ADD A NEW SLIDE
     * HTTP request: GET
     * URI template: /slides/new
     */
    public function new_slide_form() {
      SlideView::display_new_slide_form();    // call the view for form to create a new slide
    }



    /**
     * PROCESS THE SLIDE UPLOADED IN WITH THE new_slide_form() FUNCTION
     * AND ADD IT TO THE DATABASE
     * HTTP Request: POST
     * URI Template: /slides/create
     * @return the slide object on success
     * @return 0 on failure
     */
    public function create() {
      if ($_POST['submitted'] && !empty($_FILES)) {   // make sure the form was submitted and a file was uploaded to PHP
        $file = $_FILES['file'];    // grab the file
        
        // validate the submitted data
        if ($file['type'] == 'image/jpeg') {
          
          // create a new Slide object which the Model layer can access
          $slide = new Slide(array('name' => $file['name'],
                                   'filename' => strtolower(str_replace(' ', '_', $file['filename'])),
                                   'type' => $file['type'],
                                   'tmp_name' => $file['tmp_name'],
                                   'size' => $file['size']
          ));
          
          // save the slide
          $debug = $slide->save();
          if ($debug === 1) {
            SlideView::display_slide_options($slide);
          } else {
            echo $debug;
          }
        }
      }
    }
   
    


    /**
     * PROCESS THE FORM SUBMISSION OF SLIDE OPTIONS GENERATED BY EDIT()
     * HTTP Request: POST
     * URI Template: /slides/update/{id}
     * @return 1 on success
     * @return  0 on failure
     */
    public function update() {
      if (!empty($_POST) && isset($_GET['id']) && $_POST['submitted'] = 'true') {
        $id = $_GET['id'];
        $slide = Slide::get($id);
        foreach ($_POST as $key => $value) {
          if (array_key_exists($key, $slide)) {
            echo "<p>$key: $value</p>";
            $slide->$key = $value;
          }
        }

        var_dump($slide);

        if ($slide->update() === 1) {
          SlideView::display_slide_options($slide);
        }
      }
    }


    /**
     * DELETE A SLIDE FROM MEMORY, FILESYSTEM AND DATABASE
     * HTTP Request: POST
     * URI Template: /slides/destroy/{id}
     * @return [type] [description]
     */
    public function destroy() {
      if (!isset($_GET['id'])) return call('pages', 'error');    // ensure that the ID is included in the request

      if (Slide::get(intval($_GET['id']))->remove()) {    // retrieve the slide info from the database and remove it
        SlideView::index(Slide::all());
      }
    }
  }
?>