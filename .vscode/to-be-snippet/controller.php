use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\\${1:FooBar};

class ${1:FooBar}Controller extends Controller
{
    public function __construct()
    {
        \$this->middleware('scope:${2:foo-bars}::get')->only('index');
        \$this->middleware('scope:${3:foo-bar}::create')->only('store');
        \$this->middleware('scope:${3:foo-bar}::update')->only('update');
        \$this->middleware('scope:${3:foo-bar}::get')->only('show');
        \$this->middleware('scope:${3:foo-bar}::delete')->only('delete');
    }

    public function index(Request \$request)
    {
        \$apiParam = apiParam()
            ->request(\$request)
            ->generate();
        
        // get data
        \$${4:fooBars} = ${1:FooBar}::usingAPIParam(\$apiParam);

        return apiResponse()
            ->success()
            ->message('Success in Getting ${5:Foo Bars}.')
            ->data(\$${4:fooBars})
            ->logName('Get ${5:Foo Bars}')
            ->logDesc("Total results: {\$${4:fooBars}->count()}")
            ->generateWithLog();
    }

    public function show(Request \$request, \$${6:fooBar}Id)
    {
        // resource validation
        \$${6:fooBar} = ${1:FooBar}::find(\$${6:fooBar}Id);

        if (\$${6:fooBar} === null) {
            return apiResponse()
                ->failed(404)
                ->message('${7:Foo Bar} not found.')
                ->logName('Get ${7:Foo Bar}')
                ->generateWithLog();
        }

        return apiResponse()
            ->success()
            ->message('Success in Getting ${7:Foo Bar}.')
            ->data(\$${6:fooBar})
            ->logName('Get ${7:Foo Bar}')
            ->generateWithLog();
    }

    public function store(Request \$request)
    {
        // request validation
        \$validator = Validator::make(
            \$request->all(),
            [
                ${8:#Create Validations}
            ]
        );

        if (\$validator->fails()) {
            return apiResponse()
                ->failed()
                ->message('Please fill out the fields properly.')
                ->errors(\$validator->errors())
                ->logName('Create ${7:Foo Bar}')
                ->generateWithLog();
        }

        // create
        \$${6:fooBar} = ${1:FooBar}::create(\$request->all());

        return apiResponse()
            ->success(201)
            ->message('Success in Creating ${7:Foo Bar}.')
            ->logName('Create ${7:Foo Bar}')
            ->generateWithLog();
    }

    public function update(Request \$request, \$${6:fooBar}Id)
    {
        // resource validation
        \$${6:fooBar} = ${1:FooBar}::find(\$${6:fooBar}Id);

        if (\$${6:fooBar} === null) {
            return apiResponse()
                ->failed(404)
                ->message('${7:Foo Bar} not found.')
                ->logName('Update ${7:Foo Bar}')
                ->generateWithLog();
        }

        // request validation
        \$validator = Validator::make(
            \$request->all(), 
            [
                ${9:#Update Validations}
            ]
        );

        if (\$validator->fails()) {
            return apiResponse()
                ->failed()
                ->message('Please fill out the fields properly.')
                ->errors(\$validator->errors())
                ->logName('Update ${7:Foo Bar}')
                ->generateWithLog();
        }

        // update
        \$${6:fooBar}->update(\$request->all());

        return apiResponse()
            ->success()
            ->message('Success in Updating ${7:Foo Bar}.')
            ->logName('Update ${7:Foo Bar}')
            ->generateWithLog();
    }

    public function destroy(Request \$request, \$${6:fooBar}Id)
    {
        // resource validation
        \$${6:fooBar} = ${1:FooBar}::find(\$${6:fooBar}Id);

        if (\$${6:fooBar} === null) {
            return apiResponse()
                ->failed(404)
                ->message('${7:Foo Bar} not found.')
                ->logName('Delete ${7:Foo Bar}')
                ->generateWithLog();
        }

        // delete
        \$${6:fooBar}->delete();

        return apiResponse()
            ->success()
            ->message('Success in Deleting ${7:Foo Bar}.')
            ->logName('Delete ${7:Foo Bar}')
            ->generateWithLog();
    }
    $0
}