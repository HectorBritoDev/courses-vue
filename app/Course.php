<?php

namespace App;

use App\Goal;
use App\Requirement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Course
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $teacher_id
 * @property int $category_id
 * @property int $level_id
 * @property string $name
 * @property string $description
 * @property string $slug
 * @property string|null $picture
 * @property string $status
 * @property int $previous_approved
 * @property int $previous_rejected
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereLevelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course wherePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course wherePreviousApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course wherePreviousRejected($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereTeacherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereUpdatedAt($value)
 */
class Course extends Model
{
    use SoftDeletes;
    const PUBLISHED = 1;
    const PENDING = 2;
    const REJECTED = 3;

    // CONTEO DE REGISTROS BASADO EN RELACIONES (LO HACE EN CADA CONSULTA)
    protected $withCount = ['reviews', 'students'];

    protected $fillable = [
        'teacher_id',
        'category_id',
        'level_id',
        'name',
        'description',
        'picture',
        'status',
    ];
    public static function boot()
    {
        parent::boot();

        static::saving(function (Course $course) {
            if (!\App::runningInConsole()) {
                $course->slug = str_slug($course->name, '-');
            }
        });

        static::saved(function (Course $course) {
            if (!\App::runningInConsole()) {
                //Si no verificamos que se esta ejecutaando en consola, cuando ejecutemos las migraciones se va a ejecutar tambien
                if (request('requirements')) {
                    foreach (request('requirements') as $key => $requirement_input) {
                        //La $key es el indice o numero del ciclo, como si fuera la "i" de un "for", mientras que en $requirement_input se guarda el valor de los datos.
                        if ($requirement_input) {

                            Requirement::updateOrCreate(

                                //Comprobamos si el ID existe en BD, si existe actualizamos ese registro
                                ['id' => request('requirement_id' . $key)],

                                //Si no existe lo creamos
                                [
                                    'course_id' => $course->id,
                                    'requirement' => $requirement_input,
                                ]);
                        }
                    }
                }

                if (request('goals')) {
                    foreach (request('goals') as $key => $goal_input) {
                        //La $key es el indice o numero del ciclo, como si fuera la "i" de un "for", mientras que en $goal_input se guarda el valor de los datos.
                        if ($goal_input) {

                            Goal::updateOrCreate(

                                //Comprobamos si el ID existe en BD, si existe actualizamos ese registro
                                ['id' => request('goal_id' . $key)],

                                //Si no existe lo creamos
                                [
                                    'course_id' => $course->id,
                                    'goal' => $goal_input,
                                ]);
                        }
                    }
                }
            }
        });
    }

    // PARAMETERS
    public function pathAttachment()
    {
        return "/images/courses/" . $this->picture;
    }

    public function getRouteKeyName() //DEVUELVE EL ID DEL CURSO BASADO EN EL SLUG

    {
        return 'slug';
    }

    //RELATIONSHIPS
    public function category()
    {
        return $this->belongsTo('App\Category')->select('id', 'name');
    }

    public function goals()
    {
        return $this->hasMany('App\Goal')->select('id', 'course_id', 'goal');
    }

    public function level()
    {
        return $this->belongsTo('App\Level')->select('id', 'name');
    }

    public function reviews()
    {
        return $this->hasMany('App\Review')->select('id', 'user_id', 'course_id', 'rating', 'comment', 'created_at');
    }

    public function requirements()
    {
        return $this->hasMany('App\Requirement')->select('id', 'course_id', 'requirement');
    }

    public function students()
    {
        return $this->belongsToMany('App\Student');
    }

    public function teacher()
    {
        return $this->belongsTo('App\Teacher');
    }

    //ACCESSORS

    public function getRatingAttribute()
    {
        return $this->reviews->avg('rating');
    }

    //FUNCTIONS

    public function relatedCourses()
    {
        return Course::with('reviews')
            ->whereCategoryId($this->category->id) //whereCategoryId es un metodo que ya viene de con el modelo
            ->where('id', '!=', $this->id) // != DISTINTO DE
            ->latest()
            ->limit(6) //SOLO 6 RESULTADOS
            ->get();
    }

}
