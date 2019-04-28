<!--Esta inscrito el usuario?-->
@cannot('inscribe',$course)

<!--Si esta inscrito puede hacer una valoracion-->
@can('review', $course)
<div class="col-12 pt-0 mt-4 text-center">
    <h2 class="text-muted">{{ __('Escribe una valoración') }}</h2>
</div>
<div class="cointainer-fluid">
    <form action="{{ route('courses.add_review') }}" method="POST" class="form-inline" id="rating_form">
        @csrf
        <div class="form-group">
            <div class="col-12">
                <ul class="list-inline" id="list_rating" style="font-size:40px;">
                    <li class="list-inline-item star" data-number='1'><i class="fa fa-star yellow"></i></li>
                    <li class="list-inline-item star" data-number='2'><i class="fa fa-star"></i></li>
                    <li class="list-inline-item star" data-number='3'><i class="fa fa-star"></i></li>
                    <li class="list-inline-item star" data-number='4'><i class="fa fa-star"></i></li>
                    <li class="list-inline-item star" data-number='5'><i class="fa fa-star"></i></li>

                </ul>
            </div>
        </div>

        <br />

        <input type="hidden" name="rating_input" value="1" />
        <input type="hidden" name="course_id" value="{{ $course->id }}" />

        <div class="form-group">
            <div class="col-12">
                <textarea class="form-control" name="message" id="message" rows="8" cols="120 "
                    placeholder="{{ __('Escribe una reseña') }}"></textarea>
            </div>
        </div>
        <button class="btn btn-warning text-white" type="submit">
            <i class="fa fa-space-shuttle">{{ __('Valora este curso') }}</i>
        </button>
    </form>
</div>
@endcan

@endcannot

@push('scripts')
<script defer>
    jQuery(document).ready(function () {
        const ratingSelector = jQuery('#list_rating');

//Buscamos los elementos 'li' en la lista desordenada
        ratingSelector.find('li').on('click',function () {
            const number = $(this).data('number'); //Capturamos el numero de la estrella
            $('#rating_form').find('input[name=rating_input]').val(number); //Guardamos el numero de la estrella en el campo hidden del formulario

            //Le quitamos a todos los íconos la clase yellow // Ciclo que recorre cada uno de los íconos
            ratingSelector.find('li i').removeClass('yellow').each(function(index){

                //Todo ciclo empieza por 0 por ende hay que sumarle 1 al index
                // a cada ícono se le pregunta: "Eres menor o igual que el número seleccionado? de ser así se le agrega yellow"
                if ((index+1)<= number) {
                    $(this).addClass('yellow');
                }
            });

            });
    });

</script>
@endpush
