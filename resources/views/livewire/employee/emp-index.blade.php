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
                    <x-dropdown.item>
                        <x-icons.user-tie class="text-muted"/>
                        <span wire:click="createAttendance" style="cursor: pointer" class="text-muted"
                              data-bs-toggle="modal"
                              data-bs-target="#attendance">Attendance</span>
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
                            <x-form.form-group col="6">
                                <x-form.label :required="false" title="Social Status"></x-form.label>
                                <x-base.uselect wire:model="filters.social_status">
                                    <x-select.option value="0">
                                        Select Social Status
                                    </x-select.option>
                                    @foreach(\App\Enums\SocialStatus::keyValue() as $social_status)
                                        <x-select.option
                                            value="{{ $social_status['id'] }}">{{ $social_status['name'] }}</x-select.option>
                                    @endforeach
                                </x-base.uselect>
                            </x-form.form-group>

                            <x-form.form-group col="6">
                                <x-form.label title="Type"></x-form.label>
                                <x-base.uselect wire:model="filters.type">
                                    <x-select.option value="0">
                                        Select Employee Type
                                    </x-select.option>
                                    @foreach(\App\Enums\EmployeeType::keyValue() as $type)
                                        <x-select.option
                                            value="{{ $type['id'] }}">{{ $type['name'] }}</x-select.option>
                                    @endforeach
                                </x-base.uselect>
                            </x-form.form-group>

                            <x-form.form-group col="6">
                                <x-form.label title="Category"></x-form.label>
                                <x-base.uselect wire:model="filters.category_id">
                                    <x-select.option value="0">
                                        Select Category
                                    </x-select.option>
                                    @foreach($categories as $category)
                                        <x-select.option
                                            value="{{ $category->id }}">{{ $category->name }}</x-select.option>
                                    @endforeach
                                </x-base.uselect>
                            </x-form.form-group>

                            <x-form.form-group col="6">
                                <x-form.label :required="false" title="Start Date"/>
                                <x-form.date-time id="date_start" name="filters.date_start" type="text"/>
                            </x-form.form-group>

                            <x-form.form-group col="6">
                                <x-form.label :required="false" title="Min Amount"/>
                                <x-form.input :required="false" lazy="true" name="filters.amount_min" type="text"
                                              inputGroupText="EGP"/>
                            </x-form.form-group>

                            <x-form.form-group col="6">
                                <x-form.label :required="false" title="End Date"/>
                                <x-form.date-time id="date_end" name="filters.date_end" type="text"/>
                            </x-form.form-group>

                            <x-form.form-group col="6">
                                <x-form.label :required="false" title="Max Amount"/>
                                <x-form.input :required="false" lazy="true" name="filters.amount_max" type="text"
                                              inputGroupText="EGP"/>
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

                <x-table.heading>
                    Avatar
                </x-table.heading>

                <x-table.heading style="cursor: pointer" :sortable="true" wire:click="sortBy('name')" id="name"
                                 :direction="$sortDirection">
                    Name
                </x-table.heading>

                <x-table.heading style="cursor: pointer" :sortable="true" wire:click="sortBy('type')" id="type"
                                 :direction="$sortDirection">
                    Type
                </x-table.heading>

                <x-table.heading>
                    Phone
                </x-table.heading>

                <x-table.heading>
                    Address
                </x-table.heading>

                <x-table.heading>
                    Status
                </x-table.heading>

                <x-table.heading>
                    Category
                </x-table.heading>

                <x-table.heading style="cursor: pointer" :sortable="true" wire:click="sortBy('salary')" id="salary"
                                 :direction="$sortDirection">
                    Salary
                </x-table.heading>

                <x-table.heading style="cursor: pointer" :sortable="true" wire:click="sortBy('worked_date')" id="worked_date"
                                 :direction="$sortDirection">
                    Worked Date
                </x-table.heading>

                <x-table.heading>
                    Actions
                </x-table.heading>
            </x-slot>

            <x-slot name="body">

                @if($selectedPage)
                    <x-table.row>
                        <x-table.cell class="text-black" style="background: #4b455042" colspan="11">
                            <div class="text-black">
                                @unless($selectedAll)
                                    <div>
                                        <span>
                                        You have selected <strong>{{ $models->count() }}</strong> employees, do you want to select all
                                        <strong>{{ $models->total() }}</strong> employees?
                                        </span>

                                        <span wire:click="selectedAll" style="cursor: pointer;color:#0e46c5"
                                              class="ml-2">
                                            Select all
                                        </span>
                                    </div>
                                @else
                                    <span>
                                       You are currently selecting <strong>{{ $models->total() }}</strong> employees.
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
                        <x-table.cell>
                            @if(isset($model->avatar) && $model->avatar)
                                <x-base.avatar imageUrl="{{ $model->getAvatar() }}"/>
                            @endif
                        </x-table.cell>
                        <x-table.cell>
                            {{ $model->name }}
                            <br>
                            <span class="text-muted text-sm">({{ $model->nickname }})</span>
                        </x-table.cell>
                        <x-table.cell>
                            <x-base.badge type="{{\App\Enums\EmployeeType::getColor($model->type)  }}">
                                {{ \App\Enums\EmployeeType::name($model->type) }}
                            </x-base.badge>
                        </x-table.cell>
                        <x-table.cell>{{ $model->phone }}</x-table.cell>
                        <x-table.cell>{{ $model->address }}</x-table.cell>
                        <x-table.cell>
                            <x-base.badge type="{{\App\Enums\SocialStatus::getColor($model->social_status)  }}">
                                {{ \App\Enums\SocialStatus::name($model->social_status) }}
                            </x-base.badge>
                        </x-table.cell>
                        <x-table.cell>{{ $model->category->name ?? '' }}</x-table.cell>
                        <x-table.cell>{{ formatMoney($model->salary) }}</x-table.cell>
                        <x-table.cell>{{ formatDate($model->worked_date) }}</x-table.cell>


                        <x-table.cell>
                            <a href="javascript:" title="edit" wire:click="edit({{$model->id}})" style="cursor: pointer"
                               data-bs-toggle="modal" data-bs-target="#model">
                                <x-icons.edit/>
                            </a>
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.row>
                        <x-table.cell colspan="11">
                            <div class="text-center text-muted text-uppercase">
                                No employees found...
                            </div>
                        </x-table.cell>
                    </x-table.row>
                @endforelse
            </x-slot>
        </x-base.table>
        <div>
            {{ $models->links() }}
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
                    <x-form.label :required="true" title="Name"/>
                </div>
                <x-form.form-group col="8">
                    <x-form.input type="text" :required="true" lazy="true" class="round"
                                  name="employee.name"></x-form.input>
                </x-form.form-group>

                <div class="col-md-4">
                    <x-form.label title="Nickname"></x-form.label>
                </div>
                <x-form.form-group col="8">
                    <x-form.input lazy="true" name="employee.nickname" type="text"></x-form.input>
                </x-form.form-group>

                <div class="col-md-4">
                    <x-form.label required title="Type"></x-form.label>
                </div>
                <x-form.form-group col="8">
                    <x-base.uselect name="employee.type" wire:model="employee.type">
                        <x-select.option value="0">Select Employee Type</x-select.option>
                        @foreach(\App\Enums\EmployeeType::keyValue() as $status)
                            <x-select.option value="{{ $status['id'] }}">{{ $status['name'] }}</x-select.option>
                        @endforeach
                    </x-base.uselect>
                </x-form.form-group>

                <div class="col-md-4">
                    <x-form.label title="Phone"></x-form.label>
                </div>
                <x-form.form-group col="8">
                    <x-form.input lazy="true" name="employee.phone" type="number"></x-form.input>
                </x-form.form-group>

                <div class="col-md-4">
                    <x-form.label title="Address"></x-form.label>
                </div>
                <x-form.form-group col="8">
                    <x-form.input lazy="true" name="employee.address" type="text"></x-form.input>
                </x-form.form-group>

                <div class="col-md-4">
                    <x-form.label title="Worked Date"></x-form.label>
                </div>
                <x-form.form-group col="8">
                    <x-form.date-time id="worked_date" name="employee.worked_date" type="text"/>
                </x-form.form-group>

                <div class="col-md-4">
                    <x-form.label title="National ID"></x-form.label>
                </div>
                <x-form.form-group col="8">
                    <x-form.input lazy="true" name="employee.national_id" type="text"></x-form.input>
                </x-form.form-group>

                <div class="col-md-4">
                    <x-form.label title="Status"></x-form.label>
                </div>

                <x-form.form-group col="8">
                    <x-base.uselect name="employee.social_status" wire:model="employee.social_status">
                        <x-select.option value="0">Select Social Status</x-select.option>
                        @foreach(\App\Enums\SocialStatus::keyValue() as $status)
                            <x-select.option value="{{ $status['id'] }}">{{ $status['name'] }}</x-select.option>
                        @endforeach
                    </x-base.uselect>
                </x-form.form-group>

                <div class="col-md-4">
                    <x-form.label title="Category"></x-form.label>
                </div>
                <x-form.form-group col="8">
                    <x-base.uselect name="employee.category_id" wire:model="employee.category_id">
                        <x-select.option value="0">Select Category</x-select.option>
                        @foreach($categories as $category)
                            <x-select.option value="{{ $category->id }}">{{ $category->name }}</x-select.option>
                        @endforeach
                    </x-base.uselect>
                </x-form.form-group>

                <div class="col-md-4">
                    <x-form.label title="Salary"></x-form.label>
                </div>
                <x-form.form-group col="8">
                    <x-form.input :required="false" lazy="true" name="employee.salary" type="text"
                                  inputGroupText="EGP"/>
                </x-form.form-group>

                <div class="col-md-4">
                    <x-form.label title="Details"/>
                </div>
                <x-form.form-group col="8">
                    <x-form.textarea wire:model="employee.details" title="details"/>
                </x-form.form-group>

                <div class="col-md-4">
                    <x-form.label title="Avatar"/>
                    @if(isset($employee->avatar) && $employee->avatar)
                        <x-base.avatar imageUrl="{{ $employee->getAvatar() }}"/>
                    @endif
                </div>

                <x-form.form-group col="8">
                    <x-form.upload-photo name="avatar"/>
                </x-form.form-group>

            </x-base.grid>
        </x-slot>
        <x-slot name="footer">
            <x-base.button type="submit" @click="document.getElementById('form-id').submit()">Save</x-base.button>
        </x-slot>
    </x-base.modal>


    <x-base.modal id="attendance" size="lg" formAction="storeAttendance">
        <x-slot name="title">
            Attendance
        </x-slot>
        <x-slot name="content">
            <x-base.grid>
                <div class="col-md-4">
                    <x-form.label title="Date"></x-form.label>
                </div>
                <x-form.form-group col="8">
                    <x-form.date-time id="date" name="attendance.date" type="text"/>
                </x-form.form-group>
            </x-base.grid>
        </x-slot>
        <x-slot name="footer">
            <x-base.button type="submit" @click="document.getElementById('form-id').submit()">Save</x-base.button>
        </x-slot>
    </x-base.modal>
</div>


