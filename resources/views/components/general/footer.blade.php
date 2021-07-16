<footer>
    <div class="footer clearfix mb-0 text-muted">
        <div class="float-start">
            <p>{{ now()->format('Y') }} &copy; {{ config('app.name') }}</p>
        </div>
        <div class="float-end">
            <p>Powered <span class="text-danger">
                </span> by
                <a>
                    {{ config('app.name') }}
                </a>
            </p>
        </div>
    </div>
</footer>
