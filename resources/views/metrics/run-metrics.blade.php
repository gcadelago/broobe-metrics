  <div class="tab-pane fade show active" id="fill-tabpanel-0" role="tabpanel" aria-labelledby="fill-tab-0">
    <div class="row">
      <div class="col-lg-12">
            @include('metrics.partials.filters')
            @include('metrics.partials.script')

            <div class="row" id="metricCards">
            </div>
      </div>
    </div>
  </div>
<style>
.loader {
    width: 50px;
    aspect-ratio: 1;
    border-radius: 50%;
    border: 8px solid #0000;
    border-right-color: #ffa50097;
    position: relative;
    animation: l24 1s infinite linear;
  }
  .loader:before,
  .loader:after {
    content: "";
    position: absolute;
    inset: -8px;
    border-radius: 50%;
    border: inherit;
    animation: inherit;
    animation-duration: 2s;
  }
  .loader:after {
    animation-duration: 4s;
  }
  @keyframes l24 {
    100% {transform: rotate(1turn)}
  }
</style>
