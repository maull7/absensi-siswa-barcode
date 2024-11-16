# rental_mobil
`

        const scanner = new Html5QrcodeScanner('reader', {
                    // Scanner will be initialized in DOM inside element with id of 'reader'
                    qrbox: {
                        width: 250,
                        height: 250,
                    }, // Sets dimensions of scanning box (set relative to reader element width)
                    fps: 20, // Frames per second to attempt a scan
                });


                scanner.render(success, error);
                // Starts scanner

                function success(result) {

                    let today = new Date().toISOString().slice(0, 10)

                    const date_obj = new Date();

                    time = new Date().toLocaleTimeString();

                    document.getElementById('result').innerHTML = `

        <div class="card" style="width: 18rem; onload="console_log(${result});">

        <img src="../../assets/images/barcode-scan.gif" class="card-img-top" alt="...">

        <div class="card-body">

        <form id="input" action="validator_plg.php" method="post">

        <p style="font-size: 14px;" class="card-text">Bar Code Read Successfully : <span class="badge bg-primary">${result}</span></p>

        <p style="font-size: 14px;" class="card-text">Date : <span class="badge bg-primary">${today}</span></p>

        <p style="font-size: 14px;" class="card-text">Capture Time : <span class="badge bg-primary">${time}</span></p>

        <input type="hidden" name="nis" value="${result}" id="result">

        <input type="hidden" name="time_val" value="${time}" id="capture_time">

        <input type="hidden" name="date_val" value="${today}" id="capture_date">

        <input type="submit" value="Submit" style="display: none;">

        </form>

        </div>

        </div>
        `;
                    // Simpan skrip berikut di bagian bawah body atau gunakan window.onload untuk memastikan formulir telah dimuat
                    document.getElementById('input').submit();

                    // Prints result as a link inside result element

                    scanner.clear();
                    // Clears scanning instance

                    document.getElementById('reader').remove();
                    // Removes reader element from DOM since no longer needed

                }

                function error(err) {
                    console.error(err);
                    // Prints any errors to the console
                }

                function console_log(result) {
                    console.log(result);
                }
            </script>