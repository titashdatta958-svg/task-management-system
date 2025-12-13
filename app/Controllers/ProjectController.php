<?php
namespace App\Controllers;

use App\Models\ProjectModel;
use CodeIgniter\Controller;

class ProjectController extends BaseController
{
    protected $projectModel;

    public function __construct()
    {
        $this->projectModel = new ProjectModel();
    }


    // List projects
public function index()
{
    $filterName = $this->request->getGet('name');
    $startDate  = $this->request->getGet('start_date');
    $endDate    = $this->request->getGet('end_date');

    // Base query
    $builder = $this->projectModel->orderBy('created_at', 'DESC');

    // Apply filters
    if (!empty($filterName)) {
        $builder = $builder->like('name', $filterName);
    }

    if (!empty($startDate)) {
        $builder = $builder->where('start_date >=', $startDate);
    }

    if (!empty($endDate)) {
        $builder = $builder->where('end_date <=', $endDate);
    }

    // Result
    $data['projects'] = $builder->findAll();

    // Send filter values to view
    $data['filterName'] = $filterName;
    $data['startDate']  = $startDate;
    $data['endDate']    = $endDate;

    return view('projects/index', $data);
}


    // Show create form
public function create()
    {
        return view('projects/create');
    }




    // Store new project
public function store()
{
    $rules = [
        'name'       => 'required|min_length[3]',
        'description'=> 'required',
        'start_date' => 'required|valid_date[Y-m-d]',
        'end_date'   => 'required|valid_date[Y-m-d]'
    ];

    if (! $this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $start = $this->request->getPost('start_date');
    $end   = $this->request->getPost('end_date');
    $today = date('Y-m-d');

    //  Start date cannot be in the past
    if ($start < $today) {
        return redirect()->back()->withInput()
               ->with('errors', ['start_date' => 'Start date cannot be in the past.']);
    }

    //  End date cannot be in the past
    if ($end < $today) {
        return redirect()->back()->withInput()
               ->with('errors', ['end_date' => 'End date cannot be in the past.']);
    }

    //  End date must be >= start date
    if ($end < $start) {
        return redirect()->back()->withInput()
               ->with('errors', ['end_date' => 'End date must be same or after start date.']);
    }

    // Save
    $this->projectModel->save([
        'name'        => $this->request->getPost('name'),
        'description' => $this->request->getPost('description'),
        'start_date'  => $start,
        'end_date'    => $end,
    ]);

    return redirect()->to('/projects')->with('success', 'Project created.');
}







    // Show edit form
    public function edit($id = null)
    {
        $project = $this->projectModel->find($id);
        
        if (! $project) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Project with id {$id} not found");
        }

        return view('projects/edit', ['project' => $project]);
    }





    // Update project
public function update($id = null)
{
    if (! $id) {
        return redirect()->back()->withInput()->with('errors', ['Invalid project ID.']);
    }

    $rules = [
        'name'       => 'required|min_length[3]',
        'description'=> 'required',
        'start_date' => 'required|valid_date[Y-m-d]',
        'end_date'   => 'required|valid_date[Y-m-d]'
    ];

    if (! $this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $start = $this->request->getPost('start_date');
    $end   = $this->request->getPost('end_date');
    $today = date('Y-m-d');

    //  Start date cannot be in the past
    if ($start < $today) {
        return redirect()->back()->withInput()
               ->with('errors', ['start_date' => 'Start date cannot be in the past.']);
    }

    //  End date cannot be in the past
    if ($end < $today) {
        return redirect()->back()->withInput()
               ->with('errors', ['end_date' => 'End date cannot be in the past.']);
    }

    //  End date must be >= start date
    if ($end < $start) {
        return redirect()->back()->withInput()
               ->with('errors', ['end_date' => 'End date must be same or after start date.']);
    }

    // Update
    $this->projectModel->update($id, [
        'name'        => $this->request->getPost('name'),
        'description' => $this->request->getPost('description'),
        'start_date'  => $start,
        'end_date'    => $end,
    ]);

    return redirect()->to('/projects')->with('success', 'Project updated.');
}





    // Delete project
public function delete($id = null)
    {
        $this->projectModel->delete($id);
        return redirect()->to('/projects')->with('success', 'Project deleted.');
    }
}
