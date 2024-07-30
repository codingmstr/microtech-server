<?php

namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Contact;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\Reply;
use App\Models\Review;
use App\Models\Payment;
use App\Models\Reset;

class ReportResource extends JsonResource {

    public function toArray ( Request $req ) {

        $action = null;
        if ( $this->action_table == 'users' ) $action =  UserResource::make( User::find($this->action_column) );
        if ( $this->action_table == 'categories' ) $action = CategoryResource::make( Category::find($this->action_column) );
        if ( $this->action_table == 'products' ) $action = ProductResource::make( Product::find($this->action_column) );
        if ( $this->action_table == 'coupons' ) $action = CouponResource::make( Coupon::find($this->action_column) );
        if ( $this->action_table == 'orders' ) $action = OrderResource::make( Order::find($this->action_column) );
        if ( $this->action_table == 'blogs' ) $action = BlogResource::make( Blog::find($this->action_column) );
        if ( $this->action_table == 'comments' ) $action = CommentResource::make( Comment::find($this->action_column) );
        if ( $this->action_table == 'replies' ) $action = ReplyResource::make( Reply::find($this->action_column) );
        if ( $this->action_table == 'contacts' ) $action = ContactResource::make( Contact::find($this->action_column) );
        if ( $this->action_table == 'reviews' ) $action = ReviewResource::make( Review::find($this->action_column) );
        if ( $this->action_table == 'payments' ) $action = PaymentResource::make( Payment::find($this->action_column) );
        if ( $this->action_table == 'resets' ) $action = ResetResource::make( Reset::find($this->action_column) );

        return [
            'id' => $this->id,
            'table' => $this->action_table,
            'process' => $this->process,
            'ip' => $this->ip,
            'agent' => $this->agent,
            'location' => $this->location,
            'amount' => $this->amount,
            'price' => $this->price,
            'paid' => $this->paid,
            'status' => $this->status,
            'active' => $this->active,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'user' => UserResource::make( $this->user ),
            'action' => $action,
        ];

    }

}
