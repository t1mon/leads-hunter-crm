<template>
    <div>
        <div class="row my-4">
            <div class="col-12">
                <div class="card">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                            <tr>
                                <th class="text-uppercase text-xxs font-weight-bolder opacity-7">#</th>
                                <th class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Дата</th>
                                <th class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Клиент</th>
                                <th class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Класс</th>
                                <th class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Телефон</th>
                                <th class=" text-uppercase text-xxs font-weight-bolder opacity-7">№</th>
                                <th class=" text-uppercase text-xxs font-weight-bolder opacity-7">Комментарий</th>

                                <th class=" text-uppercase text-xxs font-weight-bolder opacity-7">E-MAIl</th>
                                <th class=" text-uppercase text-xxs font-weight-bolder opacity-7">Город</th>
                                <th class=" text-uppercase text-xxs font-weight-bolder opacity-7">Сумма сделки</th>
                                <th class=" text-uppercase text-xxs font-weight-bolder opacity-7">Посадочная</th>
                                <th class=" text-uppercase text-xxs font-weight-bolder opacity-7">Реферрер</th>
                                <th class=" text-uppercase text-xxs font-weight-bolder opacity-7">[UTM_SOURCE]</th>
                                <th class=" text-uppercase text-xxs font-weight-bolder opacity-7">[UTM_MEDIUM]</th>
                                <th class=" text-uppercase text-xxs font-weight-bolder opacity-7">[UTM_CAMPAIGN]</th>
                                <th class=" text-uppercase text-xxs font-weight-bolder opacity-7">ИСТОЧНИК</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(lead, index) in stateLeads">
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <p class="text-sm font-weight-normal mb-0">{{ index + 1 }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p v-date="lead.created_at" class="text-center text-sm font-weight-normal mb-0"></p>
                                </td>
                                <td>
                                    <h6 class="text-center mb-0 font-weight-normal text-sm">{{  lead.name }}</h6>
                                </td>
                                <td class="text-white text-center">
                                    <p class="mb-0 font-weight-normal text-sm">{{ stateProject.classes }}</p>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <p v-tel="lead.phone" class="mb-0 font-weight-normal text-sm"></p>
                                </td>
                                <td>
                                    <div class="text-center">
                                      <span class="badge badge-dot me-4">
                                          <i v-if="lead.entries === 1" class="bg-success"></i>
                                          <i v-if="lead.entries === 2" class="bg-warning"></i>
                                          <i v-if="lead.entries > 2" class="bg-danger"></i>
                                        <span class="text-dark text-xs"> {{ lead.entries }}</span>
                                      </span>
                                    </div>
                                </td>

<!--                                <td class="align-middle text-center text-sm">-->
<!--                                    @php-->
<!--                                    $comment = \App\Models\Project\Lead\Comment::where(-->
<!--                                    ['project_id' => $project->id, 'lead_id' => $lead->id]-->
<!--                                    )->first();-->
<!--                                    @endphp-->

<!--                                    @if(is_null($lead->comment_CRM))-->
<!--                                    @if(Auth::user()->isInProject($project))-->
<!--                                    <a class="fa fa-plus" aaria-hidden="true" href="{{route('comment.create', [$project, $lead])}}"></a>-->
<!--                                    @endif-->
<!--                                    @else-->
<!--                                    <a href="{{route('comment.show', [$project, $lead, $lead->comment_CRM])}}" class="link-info">-->
<!--                                        {{ mb_substr($lead->comment_CRM->comment_body, 0, 15) . (mb_strlen($lead->comment_CRM->comment_body) > 15 ? '…' : '') }}-->
<!--                                    </a>-->
<!--                                    @endif-->
<!--                                </td>-->

<!--                                {{&#45;&#45;Если пользователь создатель или администратор проекта, ему видны все колонки &#45;&#45;}}-->
<!--                                @if($project->isOwner() or Auth::user()->isManagerFor($project))-->
<!--                                @php-->
<!--                                $fields = ['email', 'city', 'cost', 'host', 'referrer', 'utm_source', 'utm_medium', 'utm_campaign', 'source'];-->
<!--                                @endphp-->
<!--                                @foreach($fields as $field)-->
<!--                                <td class="text-sm font-weight-normal mb-0">-->
<!--                                    {{$lead->$field}}-->
<!--                                </td>-->
<!--                                @endforeach-->
<!--                                @else {{&#45;&#45;Если пользователь наблюдатель, ему видны только колонки согласно настройкам&#45;&#45;}}-->
<!--                                @foreach($permissions->view_fields as $field)-->
<!--                                <th class="align-middle text-center">-->
<!--                                    <p class="text-sm font-weight-normal mb-0">{{ $lead->$field }}</p>-->
<!--                                </th>-->
<!--                                @endforeach-->
<!--                                @endif-->
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <spinner v-if="stateIsLoadingJ"></spinner>
    </div>
</template>

<script>
import Spinner from '../Others/Spinner'

export default {
    name: "Journal",
    components: {
        Spinner
    },
    props: ['projectid'],
    methods: {
        getLeads (id) {
            return this.$store.dispatch('getLeads', id)
        }
    },
    computed: {
        stateIsLoadingJ () {
            return this.$store.getters.stateIsLoadingJ
        },
        stateLeads () {
            return this.$store.getters.stateLeads
        },
        stateProject () {
            return this.$store.getters.stateProject
        }
    },
    async created () {
        await this.getLeads(this.projectid)
    }
}
</script>

<style scoped>

</style>
