# resnet50
resnet50 image prediction

create.table.txt 참고하여 테이블 생성.

inc.data.inc 파일을 수정. (디비 아이디, 비번, 디비명)

파이썬 서버를 실행.
<pre>
python run_keras_server.py
</pre>

잘 동작하는지 명령 프롬프트에서 테스트.
<pre>
D:\>echo muffin.dog.12.d.jpg | python simple_request.py
1. Chihuahua: 0.9682
2. toy_terrier: 0.0302
3. Pomeranian: 0.0006
4. Italian_greyhound: 0.0003
5. miniature_pinscher: 0.0003
</pre>

웹에서 index.php 접속.


샘플 웹 : http://ml.marasong.net:10080/marasong/app/

