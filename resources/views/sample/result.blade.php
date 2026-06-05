<x-layout>
    <x-slot:title>Analysis Result</x-slot>

    <div class="container animate-fade-in">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h2 style="margin-bottom: 0;">AI Analysis Result</h2>
            <a href="/analyze" class="btn" style="background: var(--bg-main); border: 1px solid var(--border); padding: 0.5rem 1rem;">Back</a>
        </div>

        <div class="result-box">
            @if(!isset($result['clarity']))
                <div style="text-align: center; padding: 2rem;">
                    <p style="color: #ef4444; font-weight: 600;">Analysis encountered an issue</p>
                    <pre style="margin-top: 1rem; text-align: left;">{{ json_encode($result, JSON_PRETTY_PRINT) }}</pre>
                </div>
            @else
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
                    @foreach(['Clarity' => 'clarity', 'Depth' => 'depth', 'Technical Relevance' => 'technical_relevance', 'Productivity' => 'productivity', 'Consistency' => 'consistency'] as $label => $key)
                        <div class="card" style="margin-bottom: 0; padding: 1rem; text-align: center;">
                            <span style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase;">{{ $label }}</span>
                            <div style="font-size: 1.25rem; font-weight: 700; color: var(--primary); margin-top: 0.25rem;">{{ $result[$key] }}</div>
                        </div>
                    @endforeach
                </div>

                <div class="card" style="background: rgba(99, 102, 241, 0.05); border-color: rgba(99, 102, 241, 0.2);">
                    <h3 style="color: var(--accent); font-size: 1.1rem; margin-bottom: 0.75rem;">Summary</h3>
                    <p style="color: var(--text-main); font-size: 1rem; line-height: 1.6;">{{ $result['summary'] }}</p>
                </div>

                <div class="card" style="border-left: 4px solid #10b981;">
                    <h3 style="color: #10b981; font-size: 1.1rem; margin-bottom: 0.75rem;">Improvement Suggestions</h3>
                    <p style="color: var(--text-main); font-size: 1rem;">{{ $result['improvement'] }}</p>
                </div>

                @if(isset($result['skills']) && count($result['skills']) > 0)
                    <div style="margin-top: 2rem;">
                        <h3 style="font-size: 1rem; color: var(--text-muted); margin-bottom: 1rem;">Skills Identified</h3>
                        <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                            @foreach($result['skills'] as $skill)
                                <span style="background: var(--bg-main); border: 1px solid var(--border); padding: 0.25rem 0.75rem; border-radius: 2rem; font-size: 0.85rem; color: var(--accent);">
                                    {{ $skill }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-layout>