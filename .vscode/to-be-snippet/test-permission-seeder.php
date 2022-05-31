
namespace Database\Seeders\Tests;

use Illuminate\Database\Seeder;
use App\Models\Systems\Permission;
use App\Models\Systems\UserType;
use App\Models\Systems\UserTypePermission;

class ${1:FooBar}PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // add test permissions
        \$permissions = [
            '${2:foo-bars}::get'   => 'Get ${4:Foo Bars}',
            '${3:foo-bar}::create' => 'Create ${5:Foo Bar}',
            '${3:foo-bar}::update' => 'Update ${5:Foo Bar}',
            '${3:foo-bar}::get'    => 'Get ${5:Foo Bar}',
            '${3:foo-bar}::delete' =>'Delete ${5:Foo Bar}'
        ];

        foreach (\$permissions as \$permissionName => \$permissionDesc) {

            Permission::create([
                'permission_name' => \$permissionName,
                'permission_desc' => \$permissionDesc
            ]);
        }

        // assign the permissions to super admin
        \$userTypes = UserType::ofName('Super Administrator')->get();
        \$permissions = Permission::ofNameLike('${3:foo-bar}%')->get();

        foreach (\$userTypes as \$userType) {

            foreach (\$permissions as \$permission) {
                UserTypePermission::create([
                    'user_type_id' => \$userType->user_type_id,
                    'permission_id' => \$permission->permission_id,
                ]);
            }
        }
    }
}
