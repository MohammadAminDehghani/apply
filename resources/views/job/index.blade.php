<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<body>

<div class="container">
    <section class="card shadow p-3 mt-2">
        <h3>Create New Job</h3>
        <form action="{{route('job.store')}}" method="POST">
            @csrf
            <div class="row mb-3">
                <div class="col-md-5">
                    <div>
                        <label  for="Title" class="form-label">Title</label>
                        <input name="title" type="text" class="form-control form-control-sm" id="title" >
                    </div>
                </div>
                <div class="col-md-2">
                    <label  for="status" class="form-label">status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="saved" selected>saved</option>
                        <option value="HR interview">HR interview</option>
                        <option value="Technical Interview">Technical Interview</option>
                        <option value="Offer">Offer</option>
                        <option value="etc">etc</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="website" class="form-label">website</label>
                    <select name="website" class="form-select form-select-sm" id="website">
                        <option value="Linkedin" selected>Linkedin</option>
                        <option value="Indeed">Indeed</option>
                        <option value="Company">Company</option>
                        <option value="etc">etc</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div>
                        <label  for="salary" class="form-label">salary</label>
                        <input name="salary" type="text" class="form-control form-control-sm" id="salary" >
                    </div>
                </div>

            </div>
            <div class="mb-3">
                <label  for="link" class="form-label">link</label>
                <input name="link" type="text" class="form-control form-control-sm" id="link" >
            </div>
            <div class="form-floating my-3">
                <textarea name="description" class="form-control h-100"  rows="8" placeholder="Job description" id="description"></textarea>
                <label for="description">Description</label>
            </div>
            <div class="form-floating my-3">
                <textarea name="note" class="form-control" placeholder="My Note..." id="note"></textarea>
                <label for="note">My Note</label>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </section>


    <section class="card shadow p-3 mt-2">
        <h3>Jobs</h3>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">title</th>
{{--                <th scope="col">website</th>--}}
{{--                <th scope="col">salary</th>--}}
                <th scope="col">date</th>
{{--                <th scope="col">status</th>--}}
                <th scope="col">Operation</th>
            </tr>
            </thead>
            <tbody>
            @foreach($jobs as $job)
                <tr>
                    <th scope="row">{{ $job->id }}</th>
                    <td>{{ $job?->title ?? '' }}</td>
{{--                    <td>{{ $job?->website ?? '' }}</td>--}}
{{--                    <td>{{ $job?->salary ?? '' }}</td>--}}
                    <td>{{ $job?->updated_at ? \Carbon\Carbon::make($job?->updated_at) : '' }}</td>
{{--                    <td>{{ $job?->status ?? '' }}</td>--}}
                    <td>
                        <div class="row">
                            <div class="col">
                                <a href="{{$job?->link}}" class="btn btn-sm btn-outline-info w-100" target="_blank" rel="noopener noreferrer">job</a>
                            </div>
                            <div class="col">
                                <a href="{{ route('job.edit', $job) }}" class="btn btn-sm btn-outline-warning w-100">edit</a>
                            </div>
                            <div class="col-md-4">
                                <form action="{{ route('job.create-cv', ['job'=>$job]) }}" method="POST">
                                    @csrf
                                    @method('POST')
                                    <div class="row">

                                        <div class="col">

                                            <input class="form-check-input" type="checkbox" name="cl" id="cl">
                                            <label class="form-label ml-2" for="cl">cl</label>
                                        </div>
                                        <div class="col"><button type="submit" class="btn btn-sm btn-outline-dark w-100">create cv</button></div>
                                    </div>


                                </form>
                            </div>
                            <div class="col">
                                <a href="{{ route('job.generate-pdf', $job) }}"
                                   class="btn btn-sm btn-outline-success w-100
                                    @if(!file_exists(storage_path('app/ai_response/'.$job->id.'.txt')))
                                        {{'disabled'}}
                                    @endif
                                "
                                >
                                    download</a>
                            </div>
                            <div class="col">
                                <form action="{{ route('job.destroy', ['job'=>$job]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger w-100">delete</button>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </section>


</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>
