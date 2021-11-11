<div class="modal fade" id="{{$modal_id}}" tabindex="-1" aria-labelledby="{{$form_id}}_label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title" id="{{$form_id}}_label">{{$title}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                {!! Form::input(
                    'text',
                    'name',
                    is_null($contact) ? '' : $contact->name,
                    [
                        'class' => 'form-control',
                        'placeholder' => trans('projects.notifications.telegram.username'),
                        'form' => $form_id,
                    ])
                !!}
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    @lang('projects.button-cancel')
                </button>
                <button form="{{$form_id}}" type="submit" class="btn btn-primary">
                    @lang('projects.button-save')
                </button>
            </div>

        </div>
    </div>
</div>