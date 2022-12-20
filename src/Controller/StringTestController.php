<?php

namespace App\Controller;

use App\Util\Result;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class StringTestController extends AbstractController
{
    #[Route('/test',methods: 'GET')]
    public function test(): JsonResponse
    {
        $str = "使用DELETE删除某个主键所在行的数据，第一次请求时，我们可能会收到 HTTP 200 状态代码，指示该数据已成功删除。如果我们再次发送此 DELETE 请求，则可能会收到 HTTP 404 作为响应，因为该项目已被删除。第二个请求没有更改服务器状态，因此即使我们得到不同的响应，DELETE操作也是幂等的。";
        $str_split = $this->mb_str_split($str, 10);
//        dd($str_split);
        $result = new Result();
        return $this->json($result->success($str_split));
    }

    /**
     * string to array
     * @param $str
     * @param int $split_length
     * @param string $charset
     * @return array|bool
     */
    function mb_str_split($str, int $split_length=1, string $charset="UTF-8"): array|bool
    {
        if(func_num_args()==1){
            return preg_split('/(?<!^)(?!$)/u', $str);
        }
        if($split_length<1)return false;
        $len = mb_strlen($str, $charset);
        $arr = array();
        for($i=0;$i<$len;$i+=$split_length){
            $s = mb_substr($str, $i, $split_length, $charset);
            $arr[] = $s;
        }
        return $arr;
    }

}
