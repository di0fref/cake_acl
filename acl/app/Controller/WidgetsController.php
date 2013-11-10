<?php
App::uses('AppController', 'Controller');
App::uses('AjaxResponse', 'Http');
/**
 * Widgets Controller
 *
 * @property Widget $Widget
 * @property PaginatorComponent $Paginator
 */
class WidgetsController extends AppController
{

	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = array('Paginator', "RequestHandler");
	public $admin_layout = "admin_layout";
	function WidgetErrorHandler()
	{
	}

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow(
			'add',
			"getWidgets",
			"getWidgetData",
			"addWidgetForm",
			"addWidget",
			"setWidgetData",
			"removeWidget",
			"saveWidgetSettings",
			"setWidgetData",
			"editWidgetForm"
		);
	}

	/**
	 * index method
	 *
	 * @return void
	 */
	public function index()
	{
		$this->Widget->recursive = 0;
		$this->set('widgets', $this->Paginator->paginate());
		$this->layout = $this->admin_layout;
	}

	/**
	 * view method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function view($id = null)
	{
		if (!$this->Widget->exists($id)) {
			throw new NotFoundException(__('Invalid widget'));
		}
		$options = array('conditions' => array('Widget.' . $this->Widget->primaryKey => $id));
		$this->set('widget', $this->Widget->find('first', $options));
		$this->layout = $this->admin_layout;
	}

	/**
	 * add method
	 *
	 * @return void
	 */
	public function add()
	{
		if ($this->request->is('post')) {
			$this->Widget->create();
			if ($this->Widget->save($this->request->data)) {
				$this->Session->setFlash(__('The widget has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The widget could not be saved. Please, try again.'));
			}
		}
		$users = $this->Widget->User->find('list');
		$this->set(compact('users'));
		$this->layout = $this->admin_layout;
	}

	/**
	 * edit method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function edit($id = null)
	{
		if (!$this->Widget->exists($id)) {
			throw new NotFoundException(__('Invalid widget'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Widget->save($this->request->data)) {
				$this->Session->setFlash(__('The widget has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The widget could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Widget.' . $this->Widget->primaryKey => $id));
			$this->request->data = $this->Widget->find('first', $options);
		}
		$users = $this->Widget->User->find('list');
		$this->set(compact('users'));
		$this->layout = $this->admin_layout;
	}

	/**
	 * delete method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function delete($id = null)
	{
		$this->Widget->id = $id;
		if (!$this->Widget->exists()) {
			throw new NotFoundException(__('Invalid widget'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Widget->delete()) {
			$this->Session->setFlash(__('The widget has been deleted.'));
		} else {
			$this->Session->setFlash(__('The widget could not be deleted. Please, try again.'));
		}
		$this->layout = $this->admin_layout;
		return $this->redirect(array('action' => 'index'));
	}

	function getWidgets()
	{
		$response = new AjaxResponse();

		$this->viewClass = "json";
		$result = $this->Widget->find("all",
			array(
				"conditions" => array(
					"user_id" => $this->Auth->user("id"),
				),
				"order" => array(
					"_column" => "asc",
					"_order" => "asc"
				)
			)
		);
		$response->setData($result);
		$this->set("response", $response->get());
		$this->set('_serialize', array("response"));
	}

	function getWidgetData()
	{
		$response = new AjaxResponse();

		$result = $this->Widget->find("first", array(
				"conditions" => array(
					"Widget.id" => $this->request->data("id"),
				),
				"fields" => array(
					"nr_of_articles_cond",
					"url",
					"id",
				)
			)
		);
		set_error_handler(array(&$this, "WidgetErrorHandler"));
		try {
			$data = file_get_contents($result["Widget"]["url"]);
			$xml = new SimpleXmlElement($data);

			$widgetData = array(
				"xml" => $xml,
				"nr_of_articles" => $result["Widget"]["nr_of_articles_cond"]
			);
			$this->set("data", $widgetData);
		} catch (Exception $e) {
			$response->setStatus(false);
			$response->setMessage("Error loading feed::" . $result["Widget"]["url"]);
			$this->set("response", $response->get());
			$this->set('_serialize', array("response"));
		}

		$this->render("entry");
	}

	function addWidgetForm()
	{

	}

	function addWidget()
	{
		$response = new AjaxResponse();

		$widget_save_data = $this->request->data;
		$widget_save_data["user_id"] = $this->Auth->user("id");

		$this->viewClass = "json";
		$response->setMessage("Widget added successfully.");
		set_error_handler(array(&$this, "WidgetErrorHandler"));
		try {
			$data = file_get_contents($this->request->data["url"]);
			$x = new SimpleXmlElement($data);

			$this->Widget->create();
			if (!$this->Widget->save(array("Widget" => $widget_save_data))) {
				$response->setStatus(false);
				$response->setMessage("Unable to add new Widget.");
			}
		} catch (Exception $e) {
			$response->setStatus(false);
			$response->setMessage("Error parsing xml::" . $this->request->data["url"] . ".");
		}
		$this->set("response", $response->get());
		$this->set('_serialize', array("response"));

	}

	function saveWidgetSettings()
	{

		$response = new AjaxResponse();

		$this->viewClass = "json";
		$response->setMessage("Widget saved successfully.");

		$widget_save_data = array(
			"id" => $this->request->data["id"],
			"nr_of_articles" => $this->request->data["nr_of_articles"]

		);
		if (!$this->Widget->save($widget_save_data)) {
			$response->setMessage("Unable to save Widget settings.");
			$response->setStatus(false);
		}
		$this->set("response", $response->get());
		$this->set('_serialize', array("response"));
	}

	function editWidgetForm()
	{
		$result = $this->Widget->find("first", array(
				"conditions" => array(
					"Widget.id" => $this->request->data["id"]
				),
				"fields" => array(
					"nr_of_articles_cond",
				)
			)
		);
		$this->set("data", $result);
	}

	function removeWidget()
	{
		$this->viewClass = "json";
		$response = new AjaxResponse();
		$response->setMessage("Widget removed.");
		if (!$this->Widget->delete($this->request->data["id"])) {
			$response->setStatus(false);
			$response->setMessage("Unable to delete Widget.");
		}
		$this->set("response", $response->get());
		$this->set('_serialize', array("response"));
	}

	function setWidgetData()
	{
		$response = new AjaxResponse();

		$this->viewClass = "json";
		$widgets = $this->request->input('json_decode');
		foreach ($widgets as $widget) {
			$save_data = array(
				"id" => $widget->id,
				"_column" => $widget->column,
				"_order" => $widget->order,
			);
			$this->Widget->save($save_data);
		}
		$response->setMessage("Widget configuration saved");
		$this->set("response", $response->get());
		$this->set('_serialize', array("response"));

	}
}
