<div>
    @section('title',$pageTitle)
    <x-base.card title="{{ $pageTitle }}">
        <x-general.progress-bar/>
        {{--RIGHT ACTIONS--}}
        <x-base.grid class="mb-3">
            {{--SEARCH--}}
            <x-base.grid-col col="3">
                <x-form.input lazy="true" type="text" class="round" name="filters.search"
                              placeholder="search employee name...">
                </x-form.input>
            </x-base.grid-col>
            {{--ADVANCED SEARCH--}}
            <x-base.grid-col col="3" style="margin-top: 7px">
                <span wire:click="toggleAdvancedSearch()" style="cursor: pointer">
                   @if($showAdvancedSearch) Hide @endif Advanced Search...
               </span>
            </x-base.grid-col>
            {{--LEFT ACTIONS--}}
            <x-base.grid-col style="padding-left:3px" col="6">
                {{--NEW--}}
                <x-base.button wire:click="create" style="float: right" data-bs-toggle="modal"
                               data-bs-target="#model">
                    <x-icons.add/>
                    New
                </x-base.button>
                {{--BulkACTIONS--}}
                <x-base.dropdown color="secondary" style="float: right" label="Bulk Actions" class="mr-2 ml-2">
                    <x-dropdown.item>
                        <x-icons.download class="text-muted"/>
                        <span style="cursor: pointer" class="text-muted"
                              wire:click="exportSelected">Export</span>
                    </x-dropdown.item>
                    <x-dropdown.item>
                        <x-icons.delete class="text-muted"/>
                        <span style="cursor: pointer" class="text-muted"
                              onclick="confirm('are you sure ?') || event.stopImmediatePropagation()"
                              wire:click="deleteSelected">Delete</span>
                    </x-dropdown.item>
                </x-base.dropdown>
                {{--PERPAGE PAGINATION--}}

                <x-base.uselect style="float: right; padding: 6px; width:120px" wire:model="perPage">
                    <x-select.option value="10">10 items</x-select.option>
                    <x-select.option value="25">25 items</x-select.option>
                    <x-select.option value="50">50 items</x-select.option>
                </x-base.uselect>

            </x-base.grid-col>
        </x-base.grid>
        <div>
            @if($showAdvancedSearch)
                <div>
                    <x-base.card style="background: #17161621" title="Filters">
                        <x-base.grid>
                            <x-form.form-group col="12">
                                <x-form.label :required="false" title="Type"></x-form.label>
                                <x-base.uselect wire:model="filters.status">
                                    <x-select.option value="0">
                                        Select Social Status
                                    </x-select.option>
                                    @foreach(\App\Enums\AttendanceTypes::keyValue() as $type)
                                        <x-select.option
                                            value="{{ $type['id'] }}">{{ $type['name'] }}
                                        </x-select.option>
                                    @endforeach
                                </x-base.uselect>
                            </x-form.form-group>

                            <x-form.form-group col="6">
                                <x-form.label :required="false" title="Start Date"/>
                                <x-form.date-time id="date_start" name="filters.date_start" type="text"/>
                            </x-form.form-group>


                            <x-form.form-group col="6">
                                <x-form.label :required="false" title="End Date"/>
                                <x-form.date-time id="date_end" name="filters.date_end" type="text"/>
                            </x-form.form-group>


                            <x-form.form-group col="6">
                                <span wire:click="resetFilters" class="text-black"
                                      style="float:right;margin-top:29px;cursor: pointer">
                                    Reset Filters
                                </span>
                            </x-form.form-group>
                        </x-base.grid>
                    </x-base.card>
                </div>
            @endif
        </div>

        {{--Table--}}
        <x-base.table class="border">
            <x-slot name="head">
                <x-table.heading>
                    <x-base.checkbox wire:model="selectedPage"/>
                </x-table.heading>

                <x-table.heading style="cursor: pointer" :sortable="true" wire:click="sortBy('date')" id="date"
                                 :direction="$sortDirection">
                    Date
                </x-table.heading>

                <x-table.heading>
                    Avatar
                </x-table.heading>

                <x-table.heading>
                    Name
                </x-table.heading>

                <x-table.heading  style="cursor: pointer" :sortable="true" wire:click="sortBy('attended')" id="attended"
                                  :direction="$sortDirection">
                    Status
                </x-table.heading>

                <x-table.heading>
                    Note
                </x-table.heading>

                <x-table.heading>
                    Actions
                </x-table.heading>
            </x-slot>

            <x-slot name="body">

                @if($selectedPage)
                    <x-table.row>
                        <x-table.cell class="text-black" style="background: #4b455042" colspan="7">
                            <div class="text-black">
                                @unless($selectedAll)
                                    <div>
                                        <span>
                                        You have selected <strong>{{ $models->count() }}</strong> attendances, do you want to select all
                                        <strong>{{ $models->total() }}</strong> attendances?
                                        </span>

                                        <span wire:click="selectedAll" style="cursor: pointer;color:#0e46c5"
                                              class="ml-2">
                                            Select all
                                        </span>
                                    </div>
                                @else
                                    <span>
                                       You are currently selecting <strong>{{ $models->total() }}</strong> attendances.
                                   </span>
                                @endunless
                            </div>
                        </x-table.cell>
                    </x-table.row>
                @endif

                @forelse($models as $model)
                    <x-table.row wire:key="{{ $model->id }}">
                        <x-table.cell>
                            <x-base.checkbox wire:model="selected" value="{{ $model->id }}"/>
                        </x-table.cell>
                        <x-table.cell>{{ formatDate($model->date) }}</x-table.cell>
                        <x-table.cell>
                            @if(isset($model->employee->avatar) && $model->employee->avatar)
                                <x-base.avatar imageUrl="{{ $model->employee->getAvatar() }}"/>
                            @endif
                        </x-table.cell>
                        <x-table.cell>
                            {{ $model->employee->name }}
                            <br>
                            <span class="text-muted text-sm">({{ $model->employee->nickname }})</span>
                        </x-table.cell>
                        <x-table.cell>
                            <x-base.badge type="{{\App\Enums\AttendanceTypes::getColor($model->attended)  }}">
                                {{ \App\Enums\AttendanceTypes::name($model->attended) }}
                            </x-base.badge>
                        </x-table.cell>
                        <x-table.cell>
                           {{ $model->note }}
                        </x-table.cell>
                        <x-table.cell>
                            <a href="javascript:" title="edit" wire:click="edit({{$model->id}})" style="cursor: pointer"
                               data-bs-toggle="modal" data-bs-target="#model">
                                <x-icons.edit/>
                            </a>
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.row>
                        <x-table.cell colspan="7">
                            <div class="text-center text-muted text-uppercase">
                                No attendances found...
                            </div>
                        </x-table.cell>
                    </x-table.row>
                @endforelse
            </x-slot>
        </x-base.table>
        <div>
            <nav aria-label="Page navigation example">
                <ul class="pagination pagination-primary">
                    {{ $models->links() }}
                </ul>
            </nav>
        </div>
    </x-base.card>
    {{--MODAL User --}}
    <x-base.modal id="model" size="lg" formAction="updateOrCreate">
        <x-slot name="title">
            Employee
        </x-slot>
        <x-slot name="content">
            <x-base.grid>

                <div class="col-md-4">
                    <x-form.label required title="Date"></x-form.label>
                </div>
                <x-form.form-group col="8">
                    <x-form.date-time id="date" name="attendance.date" type="text"/>
                </x-form.form-group>

                <div class="col-md-4">
                    <x-form.label required title="Employee"/>
                </div>
                <x-form.form-group col="8">
                    <x-base.uselect name="attendance.employee_id" wire:model="attendance.employee_id">
                        <x-select.option value="0">Select Employee</x-select.option>
                        @foreach($employees as $employee)
                            <x-select.option value="{{ $employee->id }}">{{ $employee->name }}</x-select.option>
                        @endforeach
                    </x-base.uselect>
                </x-form.form-group>

                <div class="col-md-4">
                    <x-form.label required title="type"></x-form.label>
                </div>
                <x-form.form-group col="8">
                    <x-base.uselect name="attendance.attended" wire:model="attendance.attended">
                        <x-select.option value="0">Select Attendance Type</x-select.option>
                        @foreach(\App\Enums\AttendanceTypes::keyValue() as $type)
                            <x-select.option value="{{ $type['id'] }}">{{ $type['name'] }}</x-select.option>
                        @endforeach
                    </x-base.uselect>
                </x-form.form-group>

                <div class="col-md-4">
                    <x-form.label title="Note"/>
                </div>
                <x-form.form-group col="8">
                    <x-form.textarea wire:model="attendance.note" title="note"/>
                </x-form.form-group>

            </x-base.grid>
        </x-slot>
        <x-slot name="footer">
            <x-base.button type="submit" @click="document.getElementById('form-id').submit()">Save</x-base.button>
        </x-slot>
    </x-base.modal>

</div>


