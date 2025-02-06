 <table id="projectsTable" class="table table-sm table-bordered table-striped">
                        <thead>
                            
                        </thead>
                        <tbody>
                           @foreach($projects as $project)
                            @foreach($project->requirements as $index => $requirement)
                                <tr>
                                    @if($index === 0)
                                        <td rowspan="{{ $project->requirements->count() }}">{{ $project->client->short_name }}</td>
                                        <td rowspan="{{ $project->requirements->count() }}">
                                            {{ $project->title }}
                                             @if($project->description)
                                                <button type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#descriptionModal{{ $project->id }}">
                                                    Read more
                                                </button>
                                            @endif
                                        </td>
                                    @endif
                                    <td>
                                        <div class="row">
                                            <div class="col">
                                                {{ $requirement->position }}
                                            </div>
                                            <div class="col">
                                                <a href="{{ url('/contractuals/requirements', [$requirement->project_id, $requirement->position]) }}" class="btn btn-sm btn-danger mb-1">View & Apply</a>
                                            </div>
                                        </div>

                                    </td>
                                    <td>{{ $requirement->no_of_vacancies }}</td>
                                    <td>{{ $requirement->locations }}</td>
                                    <td>{{ \Carbon\Carbon::parse($requirement->start_date_time)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($requirement->expiry_date_time)->format('d/m/Y') }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                        </tbody>
                    </table>