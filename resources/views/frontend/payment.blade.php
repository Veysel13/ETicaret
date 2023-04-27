
@extends("layouts.master")
@section("title","Odeme")
@section("content")
    <div class="container">
        <div class="bg-content">
            <h2>Ödeme</h2>
            <form action="{{route("payment")}}" method="post">
                @csrf
            <div class="row">
                <div class="col-md-5">
                    <h3>Ödeme Bilgileri</h3>
                    <div class="form-group">
                        <label for="kartno">Kredi Kartı Numarası</label>
                        <input type="text" class="form-control creditcard" id="kart_numarasi" name="kart_numarasi" style="font-size:20px;" required>
                    </div>
                    <div class="form-group">
                        <label for="son_kullanma_tarihi">Son Kullanma Tarihi</label>
                        <div class="row">
                            <div class="col-md-6">
                                Ay
                                <select name="son_kullanma_tarihi_ay" id="son_kullanma_tarihi_ay" class="form-control" required>
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                    <option>6</option>
                                    <option>7</option>
                                    <option>8</option>
                                    <option>9</option>
                                    <option>10</option>
                                    <option>11</option>
                                    <option>12</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                Yıl
                                <select name="son_kullanma_tarihi_yil" id="son_kullanma_tarihi_yil" class="form-control" required>
                                    <option>2023</option>
                                    <option>2024</option>
                                    <option>20245</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cardcvv2">CVV</label>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" class="form-control creditcard_cvv" name="cvv" id="cvv" required>
                            </div>
                        </div>
                    </div>

                        <div class="form-group">
                            <div class="checkbox">
                                <label><input type="checkbox" checked> Ön bilgilendirme formunu okudum ve kabul ediyorum.</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="checkbox">
                                <label><input type="checkbox" checked> Mesafeli satış sözleşmesini okudum ve kabul ediyorum.</label>
                            </div>
                        </div>

                    <button type="submit" class="btn btn-success btn-lg">Ödeme Yap</button>
                </div>
                <div class="col-md-7">
                    <h4>Ödenecek Tutar</h4>
                    <span class="price">18.92 <small>TL</small></span>

                    <h4>Fatura ve İletişim Bilgileri</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fullname">Ad Soyad</label>
                                <input type="text" value="{{auth()->user()->fullname}}" class="form-control" name="fullname" id="fullname" required>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="address">Adres</label>
                                <input type="text" class="form-control" value="{{$userDetail->address}}" name="address" id="address" required>
                            </div>
                        </div>
                </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="phone">Home Phone</label>
                                <input type="text" class="form-control" value="{{$userDetail->phone}}" name="phone" id="phone" required>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="phone_2">Phone</label>
                                <input type="text" class="form-control phone" value="{{$userDetail->phone_2}}" name="phone_2" id="phone_2" required>
                            </div>
                        </div>
                </div>
            </div>
            </div>
            </form>
        </div>
    </div>
@endsection

@section("footer")
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.js"></script>
    <script>
        $('.creditcard').mask('0000-0000-0000-0000', { placeholder: "____-____-____-____" });
        $('.creditcard_cvv').mask('000', { placeholder: "___" });
        $('.phone').mask('(000) 000-00-00', { placeholder: "(___) ___-__-__" });
    </script>
@endsection
