�汾v1
##����api����ԭ��
- ���е�api����/api��ͷ
- api��������,��/api/part1/part2
- part1Ϊģ����,�� user,question
- part2Ϊ��Ϊ,��add,change
- ��������ʽ������get,��/api/login?username=test1&password=123456
***
#api
***
##�汾api
## /api/version
****
##ע��api
## /api/signup
- ����
  - username �û��� ����
  - password ���� ����
***
##��¼api
## /api/login
 - ����
  - username �û��� ����
  - password ���� ����
***
##�ǳ�api
## /api/logout
***
##��������api
##/api/user/change_password
 - Ȩ��:�ѵ�¼
 - ����
   -  old_password(������) ����
   -  new_password(������) ����
***
##�һ�����api
## /api/user/reset_password
 - ����
  - phone �ֻ����� ����
***
##��֤�һ�����api
## /api/user/validate_reset_password
- ����
 - phone �ֻ����� ����
 - phone_captcha �ֻ���֤�� ����
 - new_password  ������ ����
***
##�鿴��Աapi
## /api/user/read
- ����
 - id     �û�id ����
***
##�Ƿ��Ѿ���¼api
## api/is_logged_in
***
##�������api
## /api/question/add
- Ȩ��
  - �ѵ�¼
- ����
 - title ������� ����
 - desc �������� ��ѡ
***
##��������api
## /api/question/change
- Ȩ��:�ѵ�¼����Ϊ����������
  - ����
    - id ����id ��ѡ
    - title ������� ��ѡ
    - desc �������� ��ѡ 
***
##�鿴����api
## /api/question/read
- Ȩ��:�ѵ�¼
 - ����
    - id ����id ��ѡ
    - page ҳ�� ��ѡ
    - limit ÿҳ��ʾ ��ѡ
***
##ɾ������api
## /api/question/remove
  - Ȩ��:�ѵ�¼����Ϊ����������
    - ����
      - id ����id ����
***
##��ӻش�api
## /api/answer/add
- Ȩ��:�ѵ�¼
  - ����
    - question_id ����id ����
    - content ���� ����
***
##���Ļش�api
## /api/answer/change
- Ȩ�� �ѵ�¼����Ϊ�ش�������
- ����
   - id ��id ���� 
  - content ���� ����
***
##�鿴�ش�api
## /api/answer/read
- ����
  - id �ش�id ��ѡ 
***
##�Իش�ͶƱapi
## /api/answer/vote
- Ȩ��:�ѵ�¼
- ���� 
    - answer_id �ش�id
    - vote 1Ϊͬ�� 2Ϊ����
***
##�������api
## /api/comment/add
  - Ȩ��:�ѵ�¼
  - ����
    - question_id ����id
    - answer_id �ش�id
    - reply_to ����id 
    - question_id or answer_id or reply_to ��������һ������,�������ͬʱ��ֵ,question_id ������ answer_id ������ reply_to
    - content ���� ����
***
##�鿴����api
## /api/comment/read
- ����
  - question_id ����id
  - answer_id ��id 
  - ������ѡһ,����һ��,question_id ������ answer_id
***
##ɾ������api
## /api/comment/remove
- Ȩ��:�ѵ�¼������������
  - ����
    - id ����id ����
*** 
##�����ʱ����api
## /api/timeline
- ����
  - page ҳ�� 
  - limit ÿҳ��ʾ