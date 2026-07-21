@extends('layouts.admin')

@section('page_title', 'Журнал дій системи (Аудит)')

@section('content')
<div style="background: #fff; border-radius: 16px; padding: 2.5rem; box-shadow: 0 4px 20px rgba(74, 59, 57, 0.03); border: 1px solid var(--border-color); max-width: 1100px;">
    <h3 style="font-size: 1.2rem; margin-bottom: 1.5rem; color: var(--text-main); font-weight: 600;">Історія подій та дій адміністратора</h3>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
            <thead>
                <tr style="background-color: var(--pink-bg); color: var(--text-main); border-bottom: 2px solid var(--border-color);">
                    <th style="padding: 1rem;">ID</th>
                    <th style="padding: 1rem;">Користувач</th>
                    <th style="padding: 1rem;">Дія</th>
                    <th style="padding: 1rem;">Деталі операції</th>
                    <th style="padding: 1rem;">Час події</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <td style="padding: 1rem;"><strong>#{{ $log->id }}</strong></td>
                        <td style="padding: 1rem;"><code style="background: #f8f9fa; padding: 2px 6px; border-radius: 4px;">{{ $log->user_email }}</code></td>
                        <td style="padding: 1rem;"><span style="font-weight: 600; color: #b15b4c;">{{ $log->action }}</span></td>
                        <td style="padding: 1rem; color: #555;">{{ $log->details }}</td>
                        <td style="padding: 1rem; color: #887876;">{{ $log->created_at->format('d.m.Y H:i:s') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection